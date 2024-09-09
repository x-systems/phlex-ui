<?php

declare(strict_types=1);

namespace Phlex\Ui\Tests;

use Phlex\Core\PHPUnit\TestCase;
use Phlex\Ui\Exception;
use Phlex\Ui\HtmlTemplate;
use Phlex\Ui\Lister;
use Phlex\Ui\View;

class ListerTest extends TestCase
{
    /**
     * @doesNotPerformAssertions
     */
    public function testListerRender(): void
    {
        $v = new View();
        $v->initialize();
        $l = Lister::addTo($v, ['defaultTemplate' => 'lister.html']);
        $l->setSource(['foo', 'bar']);
    }

    /**
     * Or clone lister's template from parent.
     */
    public function testListerRender2(): void
    {
        $v = new View(['template' => new HtmlTemplate('hello{list}, world{/list}')]);
        $v->initialize();
        $l = Lister::addTo($v, [], ['list']);
        $l->setSource(['foo', 'bar']);
        $this->assertSame('hello, world, world', $v->render());
    }

    public function testAddAfterRender(): void
    {
        $this->expectException(Exception::class);
        $v = new View();
        $v->initialize();
        $l = Lister::addTo($v);
        $l->setSource(['foo', 'bar']);
    }
}
