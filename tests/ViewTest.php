<?php

declare(strict_types=1);

namespace Phlex\Ui\Tests;

use Phlex\Core\Exception;
use Phlex\Core\PHPUnit\TestCase;
use Phlex\Ui\View;

class ViewTest extends TestCase
{
    /**
     * Test redering multiple times.
     */
    public function testMultipleRender(): void
    {
        $v = new View();
        $v->set('foo');

        $a = $v->render();
        $b = $v->render();
        $this->assertSame($a, $b);
    }

    public function testAddAfterRender(): void
    {
        $this->expectException(Exception::class);

        $v = new View();
        $v->set('foo');

        $a = $v->render();
        View::addTo($v);  // this should fail. No adding after rendering.
        $b = $v->render();
        $this->assertSame($a, $b);
    }
}
