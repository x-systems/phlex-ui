<?php

declare(strict_types=1);

namespace Phlex\Ui\Tests;

use Phlex\Core\PHPUnit\TestCase;
use Phlex\Ui\Table;
use Phlex\Ui\Table\Column\Link;

class TableTest extends TestCase
{
    /**
     * @doesNotPerformAssertions
     */
    public function testAddColumnWithoutModel(): void
    {
        $t = new Table();
        $t->initialize();
        $t->setSource([
            ['one' => 1, 'two' => 2, 'three' => 3, 'four' => 4],
            ['one' => 11, 'two' => 12, 'three' => 13, 'four' => 14],
        ]);

        // 4 ways to add column
        $t->addColumn(null, new Link('test.php?id=1'));

        // multiple ways to add column which doesn't exist in model
        $t->addColumn('five', new Link('test.php?id=1'));
        $t->addColumn('seven', [Link::class, ['id' => 3]]);
        $t->addColumn('eight', Link::class);
        $t->addColumn('nine');

        $t->render();
    }
}
