<?php

namespace VanEyk\MITM\Storage\Implementations\Database;

use Carbon\Carbon;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Swift_Mime_SimpleMessage;
use VanEyk\MITM\Models\StoredAttachment;
use VanEyk\MITM\Models\StoredMail;
use VanEyk\MITM\Storage\Filesystem;
use VanEyk\MITM\Storage\MailStorage;

class DatabaseStorage implements MailStorage
{
    public const TABLE_PREFIX = 'mitm';

    /** @var string|Model|Builder */
    private $model;

    /** @var Filesystem */
    private $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;

        $this->model = new StoredMail();
    }

    public function save(
        Swift_Mime_SimpleMessage $message,
        StoredMail $mail
    ): StoredMail {
        $mail->save();
        $this->saveAttachments($message, $mail);

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
                    'mime_type' => $attachment->getBodyContentType(),
                    'mail_id' => $mail->id,
                    'created_at' => now(),
                ];

                $instance = new StoredAttachment($attributes);
                $instance->setRelation('mail', $mail);
                $instance->disk = $this->filesystem->disk();

                $attachmentFilePath = $this->filesystem->relativeAttachmentContentPath(
                    $mail,
                    $instance->id
                );

                $this->filesystem->disk()->put(
                    $attachmentFilePath,
                    $attachment->getBody()
                );

                $instance->relativePathToContents = $attachmentFilePath;

                return $instance;
            });

        $mail->setRelation('attachments', $attachments);
        $mail->attachments()->saveMany($attachments);
    }

    public function paginate(int $page = 1, int $perPage = 10): Paginator
    {
        $this->model->setHidden(['source']);

        return $this->model
            ->latest()
            ->simplePaginate(
                $perPage,
                ['*'],
                $pageName = 'page',
                $page
            );
    }

    public function find($id): ?StoredMail
    {
        /** @var StoredMail|null $model */
        $model = $this->model->find($id);

        return $model;
    }

    public function findAttachment(StoredMail $mail, $id): StoredAttachment
    {
        $attachmentPath =
            $this->filesystem->relativeAttachmentContentPath($mail, $id);

        abort_if(! $this->filesystem->disk()->exists($attachmentPath), 404);

        /** @var StoredAttachment $instance */
        $instance = $mail->attachments()
            ->where('id', $id)
            ->firstOrFail();
        $instance->setRelation('mail', $mail);
        $instance->disk = $this->filesystem->disk();
        $instance->relativePathToContents = $attachmentPath;

        return $instance;
    }

    public function delete($id)
    {
        if (($model = $this->find($id)) !== null) {
            $model->delete();
        }
    }

    public function id(StoredMail $mail)
    {
        return $mail->getKey();
    }

    public function clearOlderThan(Carbon $time): int
    {
        $createdAt = (new StoredMail())->getCreatedAtColumn();

        return StoredMail::query()
            ->where($createdAt, '<=', $time)
            ->delete();
    }

    public function count(): int
    {
        return StoredMail::query()->count();
    }
}
