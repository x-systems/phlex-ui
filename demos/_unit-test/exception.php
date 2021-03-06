<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\CallbackLater;

/** @var \Phlex\Ui\App $app */
require_once __DIR__ . '/../init-app.php';

// JUST TO TEST Exceptions and Error throws

$cb = CallbackLater::addTo($app);
$cb->setUrlTrigger('m_cb');

$modal = \Phlex\Ui\Modal::addTo($app, ['cb' => $cb]);
$modal->name = 'm_test';

$modal->set(function ($m) {
    throw new \Exception('TEST!');
});

$button = \Phlex\Ui\Button::addTo($app, ['Test modal exception']);
$button->on('click', $modal->show());

$cb1 = CallbackLater::addTo($app, ['urlTrigger' => 'm2_cb']);
$modal2 = \Phlex\Ui\Modal::addTo($app, ['cb' => $cb1]);

$modal2->set(function ($m) {
    trigger_error('error triggered');
});

$button2 = \Phlex\Ui\Button::addTo($app, ['Test modal error']);
$button2->on('click', $modal2->show());
