<?php

declare(strict_types=1);

namespace Phlex\Ui\Tests;

use Phlex\Ui\Exception;
use Phlex\Ui\HtmlTemplate;

class ListerTest extends \Phlex\Core\PHPUnit\TestCase
{
    /**
     * @doesNotPerformAssertions
     */
    public function testListerRender(): void
    {
        $v = new \Phlex\Ui\View();
        $v->initialize();
        $l = \Phlex\Ui\Lister::addTo($v, ['defaultTemplate' => 'lister.html']);
        $l->setSource(['foo', 'bar']);
    }

    /**
     * Or clone lister's template from parent.
     */
    public function testListerRender2(): void
    {
        $v = new \Phlex\Ui\View(['template' => new HtmlTemplate('hello{list}, world{/list}')]);
        $v->initialize();
        $l = \Phlex\Ui\Lister::addTo($v, [], ['list']);
        $l->setSource(['foo', 'bar']);
        $this->assertSame('hello, world, world', $v->render());
    }

    public function testAddAfterRender(): void
    {
        $this->expectException(Exception::class);
        $v = new \Phlex\Ui\View();
        $v->initialize();
        $l = \Phlex\Ui\Lister::addTo($v);
        $l->setSource(['foo', 'bar']);
    }
}
