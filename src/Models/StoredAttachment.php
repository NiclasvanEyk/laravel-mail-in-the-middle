<?php

namespace VanEyk\MITM\Models;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Swift_Mime_SimpleMimeEntity;
use VanEyk\MITM\Storage\Implementations\Database\DatabaseStorage;

/**
 * @property integer $id
 * @property string|integer $mail_id
 * @property string $name
 * @property string $mime_type
 * @property-read StoredMail $mail
 */
class StoredAttachment extends Model
{
    protected $table = DatabaseStorage::TABLE_PREFIX . '_attachments';
    protected $guarded = [];

    /** @var Filesystem */
    public $disk;

    /** @var string */
    public $relativePathToContents;

    public function contents(): string
    {
        return $this->disk->get($this->relativePathToContents);
    }

    /** @return null|resource */
    public function contentsStreamed()
    {
        return $this->disk->readStream($this->relativePathToContents);
    }

    public function mail(): BelongsTo
    {
        return $this->belongsTo(StoredMail::class, 'mail_id');
    }

    public function relationsToArray()
    {
        $arrayableRelations = parent::getArrayableRelations();

        if ($this->relationLoaded('mail') && $this->mail->relationLoaded('attachments')) {
            unset($arrayableRelations['mail']);
        }

        return $arrayableRelations;
    }
}
