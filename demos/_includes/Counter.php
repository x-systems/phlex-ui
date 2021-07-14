<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

/**
 * Counter for certain demos file.
 */
class Counter extends \Phlex\Ui\Form\Control\Line
{
    public $content = 20; // default

    protected function doInitialize(): void
    {
    	parent::doInitialize();

        $this->actionLeft = new \Phlex\Ui\Button(['icon' => 'minus']);
        $this->action = new \Phlex\Ui\Button(['icon' => 'plus']);

        $this->actionLeft->js('click', $this->jsInput()->val(new \Phlex\Ui\JsExpression('parseInt([])-1', [$this->jsInput()->val()])));
        $this->action->js('click', $this->jsInput()->val(new \Phlex\Ui\JsExpression('parseInt([])+1', [$this->jsInput()->val()])));
    }
}
