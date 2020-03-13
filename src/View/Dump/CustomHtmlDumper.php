<?php

namespace VanEyk\MITM\View\Dump;

use Symfony\Component\VarDumper\Dumper\HtmlDumper;

class CustomHtmlDumper extends HtmlDumper
{
    protected $dumpHeader = '';
}
