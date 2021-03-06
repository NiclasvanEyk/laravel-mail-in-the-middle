<?php

namespace Test;

use Orchestra\Testbench\TestCase;
use VanEyk\MITM\Providers\MailInTheMiddleServiceProvider;
use VanEyk\MITM\Support\Config;

class MITMTestCase extends TestCase
{
    protected function getEnvironmentSetUp($app)
    {
        $defaults = require(__DIR__ . '/../config/mail-in-the-middle.php');
        $defaults['enabled'] = true;
        $config = array_merge($defaults, $this->mitmConfig());

        $app['config']->set(Config::KEY, $config);
        $app['config']->set('mail.default', Config::KEY);
        $app['config']->set('mail.mailers', [
            Config::KEY => [
                'transport' => Config::KEY,
            ],
        ]);
        $app['config']->set('database.default', 'testing');
    }

    protected function getPackageProviders($app)
    {
        return [
            MailInTheMiddleServiceProvider::class
        ];
    }
}
