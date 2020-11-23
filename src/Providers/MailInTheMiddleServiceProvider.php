<?php

namespace VanEyk\MITM\Providers;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use VanEyk\MITM\Auth\Ability;
use VanEyk\MITM\Console\Commands\HouseKeepingCommand;
use VanEyk\MITM\Console\Commands\SendTestMailCommand;
use VanEyk\MITM\Mail\Transport\MailInTheMiddleMailer;
use VanEyk\MITM\Storage\Filesystem;
use VanEyk\MITM\Support\Config;
use VanEyk\MITM\Storage\MailStorage;
use VanEyk\MITM\Storage\MailStorageManager;
use VanEyk\MITM\Support\Path;
use VanEyk\MITM\View\Components\AttachmentIcon;

class MailInTheMiddleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(Path::config(Config::FILE_NAME), Config::KEY);
        $this->bindClasses();
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->setupPublishes();
        }

        if (Config::get('enabled') === false) {
            return;
        }

        $this->addTransportDriver();
        $this->defineGates();
        $this->loadViewsFrom(Path::view(), Config::KEY);
        $this->loadViewComponentsAs(Config::SHORT_KEY, [
            AttachmentIcon::class,
        ]);

        if (Config::get('register_routes')) {
            $this->loadRoutesFrom(Path::routes('api.php'));
            $this->loadRoutesFrom(Path::routes('web.php'));
        }

        if (Config::get('storage_driver') === 'database') {
            $this->loadMigrationsFrom(Path::migration());
        }

        $this->commands([
            HouseKeepingCommand::class,
            SendTestMailCommand::class,
        ]);
    }

    public function defineGates()
    {
        Gate::define(Ability::DELETE_MAIL, function ($user = null, $mail) {
            return true;
        });

        Gate::define(Ability::DELETE_ALL_MAILS, function ($user = null) {
            return true;
        });
    }

    public function addTransportDriver()
    {
        $this->callAfterResolving('mail.manager', function ($mailManager) {
            $mailManager->extend(
                Config::KEY,
                function () {
                    return new MailInTheMiddleMailer(app(MailStorage::class));
                }
            );
        });
    }

    public function setupPublishes()
    {
        $this->publishes([
            Path::config() => config_path(),
        ], Config::KEY . '-config');

        $this->publishes([
            Path::migration() => database_path('/migrations'),
        ], Config::KEY . '-migrations');
    }

    public function bindClasses(): void
    {
        $this->app->singleton(MailStorageManager::class, function () {
            return new MailStorageManager($this->app);
        });

        $this->app->singleton(Filesystem::class, static function () {
            return new Filesystem(Config::get('disk'));
        });

        $this->app->bind(MailStorage::class, static function () {
            $driver = Config::get('storage_driver');
            $manager = resolve(MailStorageManager::class);

            return $manager->driver($driver);
        });
    }
}
