<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

/** @var \Phlex\Ui\App $app */
require_once __DIR__ . '/../init-app.php';

\Phlex\Ui\Header::addTo($app, ['Toast']);

$btn = \Phlex\Ui\Button::addTo($app)->set('Minimal');

$btn->on('click', new \Phlex\Ui\JsToast('Hi there!'));

$btn = \Phlex\Ui\Button::addTo($app)->set('Using a title');

$btn->on('click', new \Phlex\Ui\JsToast([
    'title' => 'Title',
    'message' => 'See I have a title',
]));

\Phlex\Ui\Header::addTo($app, ['Using class name']);

$btn = \Phlex\Ui\Button::addTo($app)->set('Success');
$btn->on('click', new \Phlex\Ui\JsToast([
    'title' => 'Success',
    'message' => 'Well done',
    'class' => 'success',
]));

$btn = \Phlex\Ui\Button::addTo($app)->set('Error');
$btn->on('click', new \Phlex\Ui\JsToast([
    'title' => 'Error',
    'message' => 'An error occured',
    'class' => 'error',
]));

$btn = \Phlex\Ui\Button::addTo($app)->set('Warning');
$btn->on('click', new \Phlex\Ui\JsToast([
    'title' => 'Warning',
    'message' => 'Behind you!',
    'class' => 'warning',
]));

\Phlex\Ui\Header::addTo($app, ['Using different position']);

$btn = \Phlex\Ui\Button::addTo($app)->set('Bottom Right');
$btn->on('click', new \Phlex\Ui\JsToast([
    'title' => 'Bottom Right',
    'message' => 'Should appear at the bottom on your right',
    'position' => 'bottom right',
]));

$btn = \Phlex\Ui\Button::addTo($app)->set('Top Center');
$btn->on('click', new \Phlex\Ui\JsToast([
    'title' => 'Top Center',
    'message' => 'Should appear at the top center',
    'position' => 'top center',
]));

\Phlex\Ui\Header::addTo($app, ['Other Options']);

$btn = \Phlex\Ui\Button::addTo($app)->set('5 seconds');
$btn->on('click', new \Phlex\Ui\JsToast([
    'title' => 'Timeout',
    'message' => 'I will stay here for 5 sec.',
    'displayTime' => 5000,
]));

$btn = \Phlex\Ui\Button::addTo($app)->set('For ever');
$btn->on('click', new \Phlex\Ui\JsToast([
    'title' => 'No Timeout',
    'message' => 'I will stay until you click me',
    'displayTime' => 0,
]));

$btn = \Phlex\Ui\Button::addTo($app)->set('Using Message style');
$btn->on('click', new \Phlex\Ui\JsToast([
    'title' => 'Awesome',
    'message' => 'I got my style from the message class',
    'class' => 'purple',
    'className' => ['toast' => 'ui message', 'title' => 'ui header'],
]));

$btn = \Phlex\Ui\Button::addTo($app)->set('With progress bar');
$btn->on('click', new \Phlex\Ui\JsToast([
    'title' => 'Awesome',
    'message' => 'See how long I will last',
    'showProgress' => 'bottom',
]));
