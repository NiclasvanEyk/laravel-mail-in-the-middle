<?php

return [
    /**
     * Per default, the package registers the necessary routes if your app is
     * in debug mode. This behaviour can be overridden by setting the
     * MAIL_IN_THE_MIDDLE_ENABLED environment variable.
     */
    'autoRegister' => env(
        'MAIL_IN_THE_MIDDLE_ENABLED',
        env('APP_DEBUG', false)
    ),

    /**
     * The path where the package gets registered if it handles the
     * route-registration for you.
     */
    'path' => '/mails',

    /**
     * The disk to use for storage. If you are using the 'filesystem'-driver
     * the metadata and rendered html of your Mailables gets stored on this
     * disk. Both supported drivers also store the attachments on this disk.
     *
     * If this is set to null, a local disk is created under
     * 'storage/app/mail-in-the-middle'
     */
    'disk' => null,

    /**
     * The implementation that is used to store your mails.
     * Available are:
     *   - filesystem (recommended if you want an easy setup that just works)
     *   - database (recommended if you want to store lots of mails)
     */
    'storage_driver' => env('MAIL_IN_THE_MIDDLE_STORAGE_DRIVER', 'filesystem'),

    'view_data' => [
        /**
         * A list of keys that will not be displayed in the "View Data"-tab in
         * the UI. The ones listed below are the public properties from the
         * {@link Illuminate\Bus\Queueable} class.
         *
         * Because *all* public properties are available to the template, these
         * would get also get displayed, but I would doubt if they are commonly
         * used so we just hide them by default. You can set this to an empty
         * array if you still want to see them.
         */
        'blacklist' => [
            'queue',
            'chainQueue',
            'connection',
            'chainConnection',
            'delay',
            'chained',
            'middleware',
        ],
    ],
];
