<?php

declare(strict_types=1);

namespace Phlex\Ui\Tests;

use Phlex\Ui\App;
use Phlex\Ui\HtmlTemplate;

class AppTest extends \Phlex\Core\PHPUnit\TestCase
{
    protected function getApp()
    {
        return new App([
            'catch_exceptions' => false,
            'always_run' => false,
        ]);
    }

    public function testTemplateClassDefault(): void
    {
        $app = $this->getApp();

        $this->assertInstanceOf(
            HtmlTemplate::class,
            $app->loadTemplate('html.html')
        );
    }

    public function testTemplateClassCustom(): void
    {
        $anotherTemplateClass = new class() extends HtmlTemplate {
        };

        $app = $this->getApp();
        $app->templateClass = get_class($anotherTemplateClass);

        $this->assertInstanceOf(
            get_class($anotherTemplateClass),
            $app->loadTemplate('html.html')
        );
    }
}
