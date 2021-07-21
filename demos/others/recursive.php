<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

/** @var \Phlex\Ui\Webpage $app */
require_once __DIR__ . '/../init-app.php';

/** @var \Phlex\Ui\View $mySwitcherClass */
$mySwitcherClass = get_class(new class() extends \Phlex\Ui\View {
    protected function doInitialize(): void
    {
        parent::doInitialize();

        \Phlex\Ui\Header::addTo($this, ['My name is ' . $this->name, 'red']);

        $buttons = \Phlex\Ui\View::addTo($this, ['ui' => 'basic buttons']);
        \Phlex\Ui\Button::addTo($buttons, ['Yellow'])->setAttribute('data-id', 'yellow');
        \Phlex\Ui\Button::addTo($buttons, ['Blue'])->setAttribute('data-id', 'blue');
        \Phlex\Ui\Button::addTo($buttons, ['Button'])->setAttribute('data-id', 'button');

        $buttons->on('click', '.button', new \Phlex\Ui\JsReload($this, [$this->name => (new \Phlex\Ui\Jquery())->data('id')]));

        switch ($this->getApp()->stickyGet($this->name)) {
            case 'yellow':
                self::addTo(\Phlex\Ui\View::addTo($this, ['ui' => 'yellow segment']));

                break;
            case 'blue':
                self::addTo(\Phlex\Ui\View::addTo($this, ['ui' => 'blue segment']));

                break;
            case 'button':
                \Phlex\Ui\Button::addTo(\Phlex\Ui\View::addTo($this, ['ui' => 'green segment']), ['Refresh page'])->link([]);

                break;
        }
    }
});

$view = \Phlex\Ui\View::addTo($app, ['ui' => 'segment']);

$mySwitcherClass::addTo($view);
