<?php

namespace VanEyk\MITM\Auth;

use VanEyk\MITM\Support\Config;

class Ability
{
    const DELETE_MAIL = Config::KEY . '.delete-mail';
    const DELETE_ALL_MAILS = Config::KEY . '.delete-all-mails';
}
