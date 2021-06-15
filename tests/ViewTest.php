<?php

declare(strict_types=1);

namespace Phlex\Ui\Tests;

use Phlex\Core\Exception;

class ViewTest extends \Phlex\Core\PHPUnit\TestCase
{
    /**
     * Test redering multiple times.
     */
    public function testMultipleRender(): void
    {
        $v = new \Phlex\Ui\View();
        $v->set('foo');

        $a = $v->render();
        $b = $v->render();
        $this->assertSame($a, $b);
    }

    public function testAddAfterRender(): void
    {
        $this->expectException(Exception::class);

        $v = new \Phlex\Ui\View();
        $v->set('foo');

        $a = $v->render();
        \Phlex\Ui\View::addTo($v);  // this should fail. No adding after rendering.
        $b = $v->render();
        $this->assertSame($a, $b);
    }
}
