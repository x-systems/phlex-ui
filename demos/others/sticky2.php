<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

/** @var \Phlex\Ui\Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

// This demo shows a local impact of a sticky parameters.

if (isset($_GET['name'])) {
    // IMPORTANT: because this is an optional frame, I have to specify it's unique elementId explicitly, othrewise
    // the name for a second frame will be affected by presence of GET['name'] parameter
    $frame = \Phlex\Ui\View::addTo($webpage, ['ui' => 'red segment', 'elementId' => 'fr1']);
    $frame->stickyGet('name');

    // frame will generate URL with sticky parameter
    \Phlex\Ui\Label::addTo($frame, ['Name:', 'detail' => $_GET['name'], 'black'])->link($frame->url());

    // app still generates URL without localized sticky
    \Phlex\Ui\Label::addTo($frame, ['Reset', 'iconRight' => 'close', 'black'])->link($webpage->url());
    \Phlex\Ui\View::addTo($frame, ['ui' => 'hidden divider']);

    // nested interractive elemetns will respect lockal sticky get
    \Phlex\Ui\Button::addTo($frame, ['Triggering callback here will inherit color'])->on('click', function () {
        return new \Phlex\Ui\JsNotify('Color was = ' . $_GET['name']);
    });

    // Next we have loader, which will dynamically load console which will dynamically output "success" message.
    \Phlex\Ui\Loader::addTo($frame)->set(function ($page) {
        \Phlex\Ui\Console::addTo($page)->set(function ($console) {
            $console->output('success!, color is still ' . $_GET['name']);
        });
    });
}

$t = \Phlex\Ui\Table::addTo($webpage);
$t->setSource(['Red', 'Green', 'Blue']);
$t->addDecorator('name', [\Phlex\Ui\Table\Column\Link::class, [], ['name']]);

$frame = \Phlex\Ui\View::addTo($webpage, ['ui' => 'green segment']);
\Phlex\Ui\Button::addTo($frame, ['does not inherit sticky get'])->on('click', function () use ($webpage) {
    return new \Phlex\Ui\JsNotify('$_GET = ' . $webpage->encodeJson($_GET));
});

\Phlex\Ui\Header::addTo($webpage, ['Use of View::url()']);

$b1 = \Phlex\Ui\Button::addTo($webpage);
$b1->set($b1->url());

\Phlex\Ui\Loader::addTo($webpage)->set(function ($page) use ($b1) {
    $b2 = \Phlex\Ui\Button::addTo($page);
    $b2->set($b2->url());

    $b2->on('click', new \Phlex\Ui\JsReload($b1));
});

$b3 = \Phlex\Ui\Button::addTo($webpage);
$b3->set($b3->url());
