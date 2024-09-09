<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\JsReload;
use Phlex\Ui\Label;
use Phlex\Ui\View;

class ReloadTest extends View
{
    protected function doInitialize(): void
    {
        parent::doInitialize();

        $label = Label::addTo($this, ['Testing...', 'detail' => '', 'red']);
        $reload = new JsReload($this, [$this->elementName => 'ok']);

        if (isset($_GET[$this->elementName])) {
            $label->class[] = 'green';
            $label->content = 'Reload success';
        } else {
            $this->js(true, $reload);
        }
    }
}
