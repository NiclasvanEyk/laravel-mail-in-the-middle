<?php

namespace VanEyk\MITM\Storage\Implementations\Filesystem;

use Carbon\Carbon;
use Illuminate\Contracts\Pagination\Paginator as PaginatorContract;
use Illuminate\Pagination\Paginator;
use Swift_Mime_SimpleMessage;
use VanEyk\MITM\Models\StoredAttachment;
use VanEyk\MITM\Storage\Filesystem;
use VanEyk\MITM\Storage\Implementations\Database\DatabaseStorage;
use VanEyk\MITM\Storage\MailStorage;
use VanEyk\MITM\Models\StoredMail;

/**
 * This lets you store mails on the filesystem.
 *
 * The mails are identified by their created_at timestamp. Each mail gets
 * its own folder that is named after their id (timestamp). Inside such a folder
 * a `stored-mail`.json contains a serialized version of the {@link StoredMail}
 * Model.
 *
 * The pagination works by getting all the foldernames and sorting them by name,
 * then calculation offsets, etc.. As you can see, the more mails you store, the
 * more work needs to be done here. For this reason the {@link DatabaseStorage}
 * is recommended if you store huge amounts of mail.
 */
class FilesystemStorage implements MailStorage
{
    // This needs to be a valid path on Windows, so no ":" or something like
    // that!
    public const TIMESTAMP_FORMAT = 'Y-m-d_H-i-s_u';

    /** @var Filesystem */
    private $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * @returns string A timestamp that identifies the passed mail or a new one
     * based on the current time
     */
    public function timestamp(?StoredMail $mail): string
    {
        if ($mail && $mail->created_at instanceof Carbon) {
            return $mail->created_at->format(static::TIMESTAMP_FORMAT);
        }

        return Carbon::now()->format(self::TIMESTAMP_FORMAT);
    }

    /**
     * @return string The path relative to the disk root, where we store
     * everything related to the mail identified by the given timestamp.
     */
    public function mailFolderPath(string $timestamp): string
    {
        return $timestamp;
    }

    public function relativeAttachmentAttributesFolderPath(
        StoredMail $mail
    ): string {
        return $this->filesystem->relativeMailFolderPath($mail)
            . '/attachments/attributes';
    }

    public function relativeAttachmentAttributesPath(
        StoredMail $mail,
        int $attachmentId
    ): string {
        return $this->relativeAttachmentAttributesFolderPath($mail)
            . '/'
            . $attachmentId
            . '.json';
    }

    /**
     * @return string The path relative to the disk root, where we can retrieve
     * the serialized {@link StoredMail} Model.
     */
    public function mailJsonPath(string $timestamp): string
    {
        return "{$this->mailFolderPath($timestamp)}/stored-mail.json";
    }

    public function id(StoredMail $mail)
    {
        return $this->timestamp($mail);
    }

    public function save(Swift_Mime_SimpleMessage $message, StoredMail $mail): StoredMail
    {
        $id = $this->id($mail);
        // This way we can retrieve the mail later based on their created_at
        // timestamp
        $mail->created_at = Carbon::createFromFormat(static::TIMESTAMP_FORMAT, $id);
        $mail->id = $id;
        $mail->setKeyType('string');

        $this->saveAttachments($message, $mail);

        $json = $mail->toJson(JSON_PRETTY_PRINT);

        $this->filesystem->disk()->put($this->mailJsonPath($id), $json);

        return $mail;
    }

    private function saveAttachments(Swift_Mime_SimpleMessage $message, StoredMail $mail)
    {
        $attachments = collect($message->getChildren())
            ->filter(static function (\Swift_Mime_SimpleMimeEntity $child) {
                return $child instanceof \Swift_Attachment;
            })
            ->map(function (\Swift_Attachment $attachment, $i) use ($mail) {
                $attributes = [
                    'id' => $i,
                    'name' => $attachment->getFilename(),
                    'mime_type' => '',
                    'mail_id' => $mail->id,
                    'created_at' => now(),
                ];

                if (method_exists($attachment, 'getBodyContentType')) {
                    $attributes['mime_type'] = $attachment->getBodyContentType();
                }

                $instance = $this->attachmentForMailFromAttributes($mail, $attributes);

                $attachmentFilePath = $this->filesystem->relativeAttachmentContentPath(
                    $mail,
                    $instance->id
                );
                $attachmentAttributesPath = $this->relativeAttachmentAttributesPath(
                    $mail,
                    $instance->id
                );

                $this->filesystem->disk()->put(
                    $attachmentFilePath,
                    $attachment->getBody()
                );

                $this->filesystem->disk()->put(
                    $attachmentAttributesPath,
                    json_encode($attributes, JSON_PRETTY_PRINT)
                );

                return $instance;
            });

        $mail->setRelation('attachments', $attachments);
    }

    public function paginate(int $page = 1, $perPage = 10): PaginatorContract
    {
        $contents = array_reverse($this->filesystem->disk()->directories());

        $offset = ($page - 1) * $perPage;
        $folders = array_slice($contents, $offset, $perPage, true);

        $items = array_map(function (string $folderName) {
            $fileContents = $this->filesystem->disk()->get($this->mailJsonPath($folderName));
            $attributes = json_decode($fileContents, true);

            return $this->mailFromAttributes($attributes)->setHidden([
                'rendered',
            ]);
        }, $folders);

        // This resets the array indices, which is necessary for the frontend to
        // properly function
        $items = array_values($items);

        $paginator = new Paginator($items, $perPage, $page);
        $paginator->hasMorePagesWhen($offset + $perPage < count($contents));

        return $paginator;
    }

    /**
     * A helper function to construct a {@link StoredMail} from the given
     * attributes.
     *
     * Because this driver does not use integers as keys, some more work needs
     * to happen here.
     */
    private function mailFromAttributes(array $attributes): StoredMail
    {
        $mail = new StoredMail();
        $mail->setKeyType('string');
        $mail->fill($attributes);

        $attachmentAttributesFolder = $this->relativeAttachmentAttributesFolderPath($mail);
        $attachmentAttributesFiles = $this->filesystem
            ->disk()
            ->files($attachmentAttributesFolder);

        $attachments = array_map(function ($attributesFile) use ($mail) {
            $attributesFileContent = $this->filesystem->disk()->get(
                $attributesFile
            );
            $attributes = json_decode($attributesFileContent, true, 512);
            $attachment = $this->attachmentForMailFromAttributes($mail, $attributes);

            // If we set the mail relation here, then the JSON-serialization
            // blows up.
            $attachment->unsetRelation('mail');

            return $attachment;
        }, $attachmentAttributesFiles);

        $mail->setRelation('attachments', collect($attachments));

        return $mail;
    }

    private function attachmentForMailFromAttributes(
        StoredMail $mail,
        array $attributes
    ): StoredAttachment {
        $instance = new StoredAttachment($attributes);
        $instance->setRelation('mail', $mail);
        $instance->disk = $this->filesystem->disk();

        $attachmentFilePath = $this->filesystem->relativeAttachmentContentPath(
            $mail,
            $instance->id
        );

        $instance->relativePathToContents = $attachmentFilePath;

        return $instance;
    }

    public function find($id): ?StoredMail
    {
        $filesInFolder = $this->filesystem->disk()->files($id);

        if (!$filesInFolder || count($filesInFolder) <= 0) {
            return null;
        }

        $jsonExists = $this->filesystem->disk()->exists($this->mailJsonPath($id));
        if (!$jsonExists) {
            return null;
        }

        $json = $this->filesystem->disk()->get($this->mailJsonPath($id));
        $attributes = json_decode($json, true);

        return $this->mailFromAttributes($attributes);
    }

    public function findAttachment(StoredMail $mail, $id): StoredAttachment
    {
        $attachmentPath =
            $this->filesystem->relativeAttachmentContentPath($mail, $id);
        $attachmentAttributesPath =
            $this->relativeAttachmentAttributesPath($mail, $id);

        abort_if(! $this->filesystem->disk()->exists($attachmentPath), 404);
        abort_if(! $this->filesystem->disk()->exists($attachmentAttributesPath), 404);

        $attributes = json_decode($this->filesystem->disk()->get($attachmentAttributesPath), true, 512);

        return $this->attachmentForMailFromAttributes($mail, $attributes);
    }

    public function delete($id)
    {
        if ($this->filesystem->disk()->exists($id)) {
            $this->filesystem->disk()->deleteDirectory($id);
        }
    }

    public function clearOlderThan(Carbon $time): int
    {
        $olderThanTimestamp = $time->format(static::TIMESTAMP_FORMAT);
        $mails = $this->filesystem->disk()->directories();
        array_unshift($mails, $olderThanTimestamp);
        sort($mails, SORT_DESC);

        $sliceEnd = array_search($olderThanTimestamp, $mails, true);
        $mailsToDelete = array_slice($mails, 0, $sliceEnd);

        foreach ($mailsToDelete as $mailDirectory) {
            $this->filesystem->disk()->deleteDirectory($mailDirectory);
        }

        return count($mailsToDelete);
    }

    public function count(): int
    {
        return count($this->filesystem->disk()->directories());
    }
}
