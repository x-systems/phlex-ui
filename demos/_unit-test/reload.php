<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\Button;
use Phlex\Ui\Callback;
use Phlex\Ui\JsReload;
use Phlex\Ui\Loader;
use Phlex\Ui\View;
use Phlex\Ui\Webpage;

/** @var Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

$v = View::addTo($webpage, ['ui' => 'segment']);
$v->set('Test');
$v->elementName = 'reload';

$b = Button::addTo($webpage)->set('Reload');
$b->on('click', new JsReload($v));

$cb = Callback::addTo($webpage);
$cb->setUrlTrigger('c_reload');

Loader::addTo($webpage, ['cb' => $cb])->set(static function ($page) {
    $v = View::addTo($page, ['ui' => 'segment'])->set('loaded');
});
