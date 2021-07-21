<?php

declare(strict_types=1);

namespace Phlex\Ui\Tests;

use Phlex\Ui\HtmlTemplate;
use Phlex\Ui\Webpage;

class WebpageTest extends \Phlex\Core\PHPUnit\TestCase
{
    protected function getApp()
    {
        return new Webpage([
            'catch_exceptions' => false,
            'always_run' => false,
        ]);
    }

    public function testTemplateClassDefault(): void
    {
        $this->assertInstanceOf(
            HtmlTemplate::class,
            $this->getApp()->loadTemplate('webpage.html')
        );
    }

    public function testTemplateClassCustom(): void
    {
        $anotherTemplateClass = new class() extends HtmlTemplate {
        };

        $webpage = $this->getApp();
        $webpage->templateClass = get_class($anotherTemplateClass);

        $this->assertInstanceOf(
            get_class($anotherTemplateClass),
            $webpage->loadTemplate('webpage.html')
        );
    }
}
