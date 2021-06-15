<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\JsExpression;
use Phlex\Ui\View;

/** @var \Phlex\Ui\App $app */
require_once __DIR__ . '/../init-app.php';

$v = View::addTo($app)->set('This will trigger a network request for testing sse...');

$sse = \Phlex\Ui\JsSse::addTo($app);
// url trigger must match php_unit test in sse provider.
$sse->setUrlTrigger('see_test');

$v->js(true, $sse->set(function () use ($sse) {
    $sse->send(new JsExpression('console.log("test")'));
    $sse->send(new JsExpression('console.log("test")'));
    $sse->send(new JsExpression('console.log("test")'));
    $sse->send(new JsExpression('console.log("test")'));

    // non-SSE way
    return $sse->send(new JsExpression('console.log("test")'));
}));
