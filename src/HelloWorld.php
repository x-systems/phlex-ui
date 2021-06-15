<?php

declare(strict_types=1);

namespace Phlex\Ui;

/**
 * Implements Hello World. Add this view anywhere!
 */
class HelloWorld extends View
{
    protected function doInitialize(): void
    {
        parent::doInitialize();
        $this->set('Content', 'Hello World');
    }
}
