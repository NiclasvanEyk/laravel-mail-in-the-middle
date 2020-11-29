<?php

namespace VanEyk\MITM\Storage;

use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Swift_Mime_SimpleMessage;
use VanEyk\MITM\Models\StoredAttachment;
use VanEyk\MITM\Models\StoredMail;

/**
 * @template IdType
 */
interface MailStorage
{
    public function save(Swift_Mime_SimpleMessage $message, StoredMail $mail): StoredMail;

    /** @return string|integer */
    public function id(StoredMail $mail);

    public function paginate(int $page = 1, int $perPage = 10): Paginator;

    /**
     * @param IdType $id
     * @return StoredMail|null
     */
    public function find($id): ?StoredMail;

    /**
     * @param StoredMail $mail
     * @param IdType $id
     * @return StoredAttachment
     */
    public function findAttachment(StoredMail $mail, $id): StoredAttachment;

    public function count(): int;

    /**
     * @param IdType $id
     */
    public function delete($id): void;

    public function clearOlderThan(Carbon $time): int;
}
