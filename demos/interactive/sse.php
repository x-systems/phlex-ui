<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

/** @var \Phlex\Ui\Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

\Phlex\Ui\Header::addTo($webpage, ['SSE with ProgressBar']);

$bar = \Phlex\Ui\ProgressBar::addTo($webpage);

$button = \Phlex\Ui\Button::addTo($webpage, ['Turn On']);
$buttonStop = \Phlex\Ui\Button::addTo($webpage, ['Turn Off']);
// non-SSE way
//$button->on('click', $bar->js()->progress(['percent'=> 40]));

$sse = \Phlex\Ui\JsSse::addTo($webpage, ['showLoader' => true]);

$button->on('click', $sse->set(function () use ($button, $sse, $bar) {
    $sse->send($button->js()->addClass('disabled'));

    $sse->send($bar->jsValue(20));
    sleep(1);
    $sse->send($bar->jsValue(40));
    sleep(1);
    $sse->send($bar->jsValue(60));
    sleep(2);
    $sse->send($bar->jsValue(80));
    sleep(1);

    // non-SSE way
    return [
        $bar->jsValue(100),
        $button->js()->removeClass('disabled'),
    ];
}));

$buttonStop->on('click', [$button->js()->atkServerEvent('stop'), $button->js()->removeClass('disabled')]);

\Phlex\Ui\View::addTo($webpage, ['ui' => 'divider']);
\Phlex\Ui\Header::addTo($webpage, ['SSE operation with user confirmation']);

$sse = \Phlex\Ui\JsSse::addTo($webpage);
$button = \Phlex\Ui\Button::addTo($webpage, ['Click me to change my text']);

$button->on('click', $sse->set(function ($jsChain) use ($sse, $button) {
    $sse->send($button->js()->text('Please wait for 2 seconds...'));
    sleep(2);

    return $button->js()->text($sse->args['newButtonText']);
}, ['newButtonText' => 'This is my new text!']), ['confirm' => 'Please confirm that you wish to continue']);
