<?php

declare(strict_types=1);

namespace Phlex\Ui\Tests;


class TableTest extends \Phlex\Core\PHPUnit\TestCase
{
    /**
     * @doesNotPerformAssertions
     */
    public function testAddColumnWithoutModel(): void
    {
        $t = new \Phlex\Ui\Table();
        $t->initialize();
        $t->setSource([
            ['one' => 1, 'two' => 2, 'three' => 3, 'four' => 4],
            ['one' => 11, 'two' => 12, 'three' => 13, 'four' => 14],
        ]);

        // 4 ways to add column
        $t->addColumn(null, new \Phlex\Ui\Table\Column\Link('test.php?id=1'));

        // multiple ways to add column which doesn't exist in model
        $t->addColumn('five', new \Phlex\Ui\Table\Column\Link('test.php?id=1'));
        $t->addColumn('seven', [\Phlex\Ui\Table\Column\Link::class, ['id' => 3]]);
        $t->addColumn('eight', \Phlex\Ui\Table\Column\Link::class);
        $t->addColumn('nine');

        $t->render();
    }
}
