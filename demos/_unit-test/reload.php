<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\Button;
use Phlex\Ui\Callback;
use Phlex\Ui\JsReload;
use Phlex\Ui\View;

/** @var \Phlex\Ui\App $app */
require_once __DIR__ . '/../init-app.php';

$v = View::addTo($app, ['ui' => 'segment']);
$v->set('Test');
$v->name = 'reload';

$b = Button::addTo($app)->set('Reload');
$b->on('click', new JsReload($v));

$cb = Callback::addTo($app);
$cb->setUrlTrigger('c_reload');

\Phlex\Ui\Loader::addTo($app, ['cb' => $cb])->set(function ($page) {
    $v = View::addTo($page, ['ui' => 'segment'])->set('loaded');
});
