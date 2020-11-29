<?php

namespace VanEyk\MITM\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use VanEyk\MITM\Storage\Implementations\Database\DatabaseStorage;
use VanEyk\MITM\Storage\Implementations\Filesystem\FilesystemStorage;

/**
 * A mail that was meant to be sent out by the application.
 *
 * This model contains helpful debug information
 *
 * @property int|string $id
 * @property string $subject
 * @property string $from
 * @property string $to
 * @property string|null $cc
 * @property string|null $bcc
 * @property integer $priority
 * @property string $src
 * @property string $rendered
 * @property string $viewData
 * @property Carbon $created_at
 * @mixin Builder
 */
class StoredMail extends Model
{
    // We only need created_at, which is set on the database side, so we have no
    // need for it here.
    public $timestamps = false;
    protected $table = DatabaseStorage::TABLE_PREFIX . '_mails';
    protected $guarded = [];
    protected $dateFormat = FilesystemStorage::TIMESTAMP_FORMAT;
    protected $with = ['attachments'];
    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function attachments(): HasMany
    {
        return $this->hasMany(StoredAttachment::class, 'mail_id');
    }
}
