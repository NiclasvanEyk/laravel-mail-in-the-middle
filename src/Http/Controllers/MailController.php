<?php

namespace VanEyk\MITM\Http\Controllers;

use Illuminate\Http\Request;
use VanEyk\MITM\Storage\MailStorage;

class MailController
{
    /** @var MailStorage */
    private $storage;

    public function __construct(MailStorage $storage)
    {
        $this->storage = $storage;
    }

    public function content($id)
    {
        $mail = $this->storage->find($id);

        abort_if($mail === null, 404);

        return $mail->rendered;
    }

    public function downloadAttachment($mailId, $attachmentId)
    {
        $mail = $this->storage->find($mailId);

        abort_if($mail === null, 404);

        $attachment = $this->storage->findAttachment($mail, $attachmentId);

        abort_if($mail === null, 404);

        return response($attachment->contents())->withHeaders([
            'content-disposition' => 'attachment; filename="' . $attachment->name . '"',
            'content-type' => $attachment->mime_type,
        ]);
    }

    public function index(Request $request)
    {
        $driver = config('mail.driver');

        return $this->storage->paginate($request->get('page', 1));
    }

    public function show($id)
    {
        $mail = $this->storage->find($id);

        abort_if($mail === null, 404);

        return $mail;
    }

    public function destroy($id)
    {
        $this->storage->delete($id);

        return response()->noContent();
    }
}
