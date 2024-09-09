<?php

declare(strict_types=1);

namespace Phlex\Ui\Tests;

use Phlex\Core\PHPUnit\TestCase;
use Phlex\Ui\Button;

class ButtonTest extends TestCase
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
