<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\Button;
use Phlex\Ui\Header;
use Phlex\Ui\JsToast;
use Phlex\Ui\Webpage;

/** @var Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

Header::addTo($webpage, ['Toast']);

$btn = Button::addTo($webpage)->set('Minimal');

$btn->on('click', new JsToast('Hi there!'));

$btn = Button::addTo($webpage)->set('Using a title');

$btn->on('click', new JsToast([
    'title' => 'Title',
    'message' => 'See I have a title',
]));

Header::addTo($webpage, ['Using class name']);

$btn = Button::addTo($webpage)->set('Success');
$btn->on('click', new JsToast([
    'title' => 'Success',
    'message' => 'Well done',
    'class' => 'success',
]));

$btn = Button::addTo($webpage)->set('Error');
$btn->on('click', new JsToast([
    'title' => 'Error',
    'message' => 'An error occured',
    'class' => 'error',
]));

$btn = Button::addTo($webpage)->set('Warning');
$btn->on('click', new JsToast([
    'title' => 'Warning',
    'message' => 'Behind you!',
    'class' => 'warning',
]));

Header::addTo($webpage, ['Using different position']);

$btn = Button::addTo($webpage)->set('Bottom Right');
$btn->on('click', new JsToast([
    'title' => 'Bottom Right',
    'message' => 'Should appear at the bottom on your right',
    'position' => 'bottom right',
]));

$btn = Button::addTo($webpage)->set('Top Center');
$btn->on('click', new JsToast([
    'title' => 'Top Center',
    'message' => 'Should appear at the top center',
    'position' => 'top center',
]));

Header::addTo($webpage, ['Other Options']);

$btn = Button::addTo($webpage)->set('5 seconds');
$btn->on('click', new JsToast([
    'title' => 'Timeout',
    'message' => 'I will stay here for 5 sec.',
    'displayTime' => 5000,
]));

$btn = Button::addTo($webpage)->set('For ever');
$btn->on('click', new JsToast([
    'title' => 'No Timeout',
    'message' => 'I will stay until you click me',
    'displayTime' => 0,
]));

$btn = Button::addTo($webpage)->set('Using Message style');
$btn->on('click', new JsToast([
    'title' => 'Awesome',
    'message' => 'I got my style from the message class',
    'class' => 'purple',
    'className' => ['toast' => 'ui message', 'title' => 'ui header'],
]));

$btn = Button::addTo($webpage)->set('With progress bar');
$btn->on('click', new JsToast([
    'title' => 'Awesome',
    'message' => 'See how long I will last',
    'showProgress' => 'bottom',
]));
