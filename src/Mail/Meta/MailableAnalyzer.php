<?php

namespace VanEyk\MITM\Mail\Meta;

use Illuminate\Mail\Mailable;
use Illuminate\Support\Arr;
use Illuminate\View\ViewFinderInterface;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use VanEyk\MITM\Support\Config;
use VanEyk\MITM\View\Dump\CustomHtmlDumper;

class MailableAnalyzer
{
    /** @var Mailable */
    private $mailable;

    /** @noRector Rector\SOLID\Rector\ClassMethod\UseInterfaceOverImplementationInConstructorRector */
    public function __construct(Mailable $mailable)
    {
        $this->mailable = $mailable;
        $this->view = app('view.finder');
    }

    public function viewDataArray(): array
    {
        return Arr::except(
            $this->mailable->buildViewData(),
            Config::get('view_data.blacklist')
        );
    }

    public function viewDataDump(): string
    {
        $cloner = new VarCloner();
        $dumper = new CustomHtmlDumper('php://memory');
        $dumper->setTheme('light');
        return $dumper->dump($cloner->cloneVar($this->viewDataArray()), true);
    }

    public function source(): string
    {
        if ($html = $this->forceAccess($this->mailable, 'html')) {
            return $html;
        }

        if ($markdown = $this->forceAccess($this->mailable, 'markdown')) {
            return file_get_contents($this->getViewSource($markdown));
        }

        if ($this->mailable->view) {
            return file_get_contents($this->getViewSource($this->mailable->view));
        }

        return '';
    }

    private function getViewSource(string $name): string
    {
        return app('view')->getFinder()->find($name);
    }

    private function forceAccess($object, string $propertyName)
    {
        $reflection = new \ReflectionClass($object);
        $property = $reflection->getProperty($propertyName);
        $property->setAccessible(true);
        return $property->getValue($object);
    }

    private function getViewSource(string $name): string
    {
        return app('view')->getFinder()->find($name);
    }
}
