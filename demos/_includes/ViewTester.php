<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

/**
 * This view is designed to verify various things about it's positioning, e.g.
 * can its callbacks reach itself and potentially more.
 */
class ViewTester extends \Phlex\Ui\View
{
    protected function doInitialize(): void
    {
        parent::doInitialize();

        $label = \Phlex\Ui\Label::addTo($this, ['CallBack', 'detail' => 'fail', 'red']);
        $reload = new \Phlex\Ui\JsReload($this, [$this->elementName => 'ok']);

        if (isset($_GET[$this->elementName])) {
            $label->class[] = 'green';
            $label->detail = 'success';
        } else {
            $this->js(true, $reload);
            $this->js(true, new \Phlex\Ui\JsExpression('var s = Date.now(); var i=setInterval(function() { var p = Date.now()-s; var el=$[]; el.find(".detail").text(p+"ms"); if(el.is(".green")) { clearInterval(i); }}, 100)', [$label]));
        }
    }
}
