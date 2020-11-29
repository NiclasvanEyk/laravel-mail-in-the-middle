<?php

namespace VanEyk\MITM\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use VanEyk\MITM\Console\Commands\HouseKeepingCommand;
use VanEyk\MITM\Console\Commands\SendTestMailCommand;
use VanEyk\MITM\Storage\MailStorage;
use VanEyk\MITM\Support\Config;
use VanEyk\MITM\Support\Route;
use VanEyk\MITM\Support\View;

class MailController
{
    /** @var MailStorage */
    private $storage;

    public function __construct(MailStorage $storage)
    {
        $this->storage = $storage;
    }

    /**
     * Downloads an attachment.
     */
    public function downloadAttachment($mailId, $attachmentId)
    {
        $mail = $this->storage->find($mailId);

        abort_if($mail == null, 404);

        $attachment = $this->storage->findAttachment($mail, $attachmentId);

        abort_if($attachment == null, 404);

        return response($attachment->contents())->withHeaders([
            'content-disposition' => 'attachment; filename="' . $attachment->name . '"',
            'content-type' => $attachment->mime_type,
        ]);
    }

    /**
     * Sends a test mail, so the user can see that the package works.
     */
    public function sendTestMail()
    {
        Artisan::call(SendTestMailCommand::class);

        return back();
    }

    /**
     * Deletes all mails currently stored.
     */
    public function clearAll()
    {
        Artisan::call(HouseKeepingCommand::class, [
            'relativeDateInPast' => 'next year',
        ]);

        return back();
    }

    /**
     * Renders the overview of all stored mails.
     */
    public function index(Request $request)
    {
        $perPage = 8;
        $page = max(1, min($perPage, $request->get('page', 1)));
        $mails = $this->storage->paginate($page, $perPage);
        $mails->setPath(route(Route::name('mail-overview'), [], false));
        $selectedMailIndex = max(0, min(count($mails->items()) - 1, $request->get('index', 0)));

        return view(View::pageName('mails-overview-new'), [
            'mails' => $mails,
            'mailerUnused' => !Config::usesMailInTheMiddle(),
            'selectedMailIndex' => $selectedMailIndex,
            'transport' => Config::mailDriverInUse(),
            'envKey' => Config::isBeforeLaravel7() ? 'MAIL_DRIVER' : 'MAIL_MAILER',
        ]);
    }

    /**
     * Renders the detail view of a mail.
     */
    public function show($id)
    {
        $mail = $this->storage->find($id);

        abort_if($mail === null, 404);

        return View::page('mail-detail', compact('mail'));
    }

    /**
     * Deletes a stored mail.
     */
    public function destroy($id)
    {
        $this->storage->delete($id);

        return back();
    }
}
