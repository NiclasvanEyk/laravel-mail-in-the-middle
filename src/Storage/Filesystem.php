<?php

namespace VanEyk\MITM\Storage;

use Illuminate\Contracts\Filesystem\Filesystem as FilesystemContract;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem as LeagueFilesystem;
use VanEyk\MITM\Models\StoredAttachment;
use VanEyk\MITM\Models\StoredMail;
use VanEyk\MITM\Support\Config;

/**
 * A helper class to access the configured disk and some paths that are shared
 * between the storage drivers.
 */
class Filesystem
{
    public const DEFAULT_DISK_NAME = Config::KEY;

    /** @var string */
    private $diskName;

    public function __construct(?string $diskName)
    {
        if ($diskName === null) {
            $this->createDefaultDisk();
            $diskName = static::DEFAULT_DISK_NAME;
        }

        $this->diskName = $diskName;
    }

    private function createDefaultDisk(): void
    {
        $root = storage_path('app/' . static::DEFAULT_DISK_NAME);
        $local = new FilesystemAdapter(new LeagueFilesystem(new Local($root)));

        /** @var FilesystemManager $manager */
        $manager = app('filesystem');
        $manager->set(static::DEFAULT_DISK_NAME, $local);
    }

    public function relativeMailFolderPath(StoredMail $mail): string
    {
        return $mail->id;
    }

    public function relativeAttachmentContentPath(
        StoredMail $mail,
        int $attachmentId
    ): string {
        $mailFolderPath =$this->relativeMailFolderPath($mail);

        return $mailFolderPath . '/attachments/content/' . $attachmentId;
    }

    public function disk(): FilesystemContract
    {
        return Storage::disk($this->diskName);
    }
}
