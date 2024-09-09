<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\Button;
use Phlex\Ui\Header;
use Phlex\Ui\Jquery;
use Phlex\Ui\JsReload;
use Phlex\Ui\View;
use Phlex\Ui\Webpage;

/** @var Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

/** @var View $mySwitcherClass */
$mySwitcherClass = get_class(new class() extends View {
    protected function doInitialize(): void
    {
        parent::doInitialize();

        Header::addTo($this, ['My name is ' . $this->elementName, 'red']);

        $buttons = View::addTo($this, ['ui' => 'basic buttons']);
        Button::addTo($buttons, ['Yellow'])->setAttribute('data-id', 'yellow');
        Button::addTo($buttons, ['Blue'])->setAttribute('data-id', 'blue');
        Button::addTo($buttons, ['Button'])->setAttribute('data-id', 'button');

        $buttons->on('click', '.button', new JsReload($this, [$this->elementName => (new Jquery())->data('id')]));

        switch ($this->getApp()->stickyGet($this->elementName)) {
            case 'yellow':
                self::addTo(View::addTo($this, ['ui' => 'yellow segment']));

                break;
            case 'blue':
                self::addTo(View::addTo($this, ['ui' => 'blue segment']));

                break;
            case 'button':
                Button::addTo(View::addTo($this, ['ui' => 'green segment']), ['Refresh page'])->link([]);

                break;
        }
    }
});

$view = View::addTo($webpage, ['ui' => 'segment']);

$mySwitcherClass::addTo($view);
