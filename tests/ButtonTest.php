<?php

declare(strict_types=1);

namespace Phlex\Ui\Tests;

use Phlex\Ui\Button;

class ButtonTest extends \Phlex\Core\PHPUnit\TestCase
{
    /**
     * @doesNotPerformAssertions
     */
    public function testButtonIcon(): void
    {
        $b = new Button(['Load', 'icon' => 'pause']);
        $b->render();
    }
}
