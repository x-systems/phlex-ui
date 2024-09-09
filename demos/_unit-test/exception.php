<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\Button;
use Phlex\Ui\CallbackLater;
use Phlex\Ui\Modal;
use Phlex\Ui\Webpage;

/** @var Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

// JUST TO TEST Exceptions and Error throws

$cb = CallbackLater::addTo($webpage);
$cb->setUrlTrigger('m_cb');

$modal = Modal::addTo($webpage, ['cb' => $cb]);
$modal->elementName = 'm_test';

$modal->set(static function ($m) {
    throw new \Exception('TEST!');
});

$button = Button::addTo($webpage, ['Test modal exception']);
$button->on('click', $modal->show());

$cb1 = CallbackLater::addTo($webpage, ['urlTrigger' => 'm2_cb']);
$modal2 = Modal::addTo($webpage, ['cb' => $cb1]);

$modal2->set(static function ($m) {
    trigger_error('error triggered');
});

$button2 = Button::addTo($webpage, ['Test modal error']);
$button2->on('click', $modal2->show());
