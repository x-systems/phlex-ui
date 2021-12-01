<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

class ReloadTest extends \Phlex\Ui\View
{
    protected function doInitialize(): void
    {
        parent::doInitialize();

        $label = \Phlex\Ui\Label::addTo($this, ['Testing...', 'detail' => '', 'red']);
        $reload = new \Phlex\Ui\JsReload($this, [$this->elementName => 'ok']);

        if (isset($_GET[$this->elementName])) {
            $label->class[] = 'green';
            $label->content = 'Reload success';
        } else {
            $this->js(true, $reload);
        }
    }
}
