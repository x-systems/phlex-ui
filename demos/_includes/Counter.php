<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\Button;
use Phlex\Ui\Form\Control\Line;
use Phlex\Ui\JsExpression;

/**
 * Counter for certain demos file.
 */
class Counter extends Line
{
    public $content = 20; // default

    protected function doInitialize(): void
    {
        parent::doInitialize();

        $this->actionLeft = new Button(['icon' => 'minus']);
        $this->action = new Button(['icon' => 'plus']);

        $this->actionLeft->js('click', $this->jsInput()->val(new JsExpression('parseInt([])-1', [$this->jsInput()->val()])));
        $this->action->js('click', $this->jsInput()->val(new JsExpression('parseInt([])+1', [$this->jsInput()->val()])));
    }
}
