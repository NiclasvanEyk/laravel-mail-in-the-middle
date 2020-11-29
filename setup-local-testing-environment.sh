#!/usr/bin/env bash

set -e

cd ../
rm -rf laravel-mail-in-the-middle-testing

# Create a fresh laravel project
composer create-project --prefer-dist laravel/laravel laravel-mail-in-the-middle-testing
cd laravel-mail-in-the-middle-testing

# Add our local version of mail-in-the-middle as a source, so composer installs the local version
sed -i 's/"require": {/"repositories": [{"type": "path","url": "..\/laravel-mail-in-the-middle"}],\n    "require": {/' composer.json

# Install and symlink our local version of the package into the fresh project
composer require niclas-van-eyk/laravel-mail-in-the-middle @dev

# Create and use the mailer as described in the readme
sed -i "s/'mailers' => \[/'mailers' => \[\n\n        'mail-in-the-middle' => \[\n            'transport' => 'mail-in-the-middle',\n        ],\n/" config/mail.php
sed -i 's/MAIL_MAILER=smtp/MAIL_MAILER=mail-in-the-middle/' .env
