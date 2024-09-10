<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\Button;
use Phlex\Ui\Console;
use Phlex\Ui\Header;
use Phlex\Ui\JsNotify;
use Phlex\Ui\JsReload;
use Phlex\Ui\Label;
use Phlex\Ui\Loader;
use Phlex\Ui\Table;
use Phlex\Ui\Table\Column\Link;
use Phlex\Ui\View;
use Phlex\Ui\Webpage;

/** @var Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

// This demo shows a local impact of a sticky parameters.

if (isset($_GET['name'])) {
    // IMPORTANT: because this is an optional frame, I have to specify it's unique elementId explicitly, othrewise
    // the name for a second frame will be affected by presence of GET['name'] parameter
    $frame = View::addTo($webpage, ['ui' => 'red segment', 'elementId' => 'fr1']);
    $frame->stickyGet('name');

    // frame will generate URL with sticky parameter
    Label::addTo($frame, ['Name:', 'detail' => $_GET['name'], 'black'])->link($frame->url());

    // app still generates URL without localized sticky
    Label::addTo($frame, ['Reset', 'iconRight' => 'close', 'black'])->link($webpage->url());
    View::addTo($frame, ['ui' => 'hidden divider']);

    // nested interractive elemetns will respect lockal sticky get
    Button::addTo($frame, ['Triggering callback here will inherit color'])->on('click', function () {
        return new JsNotify('Color was = ' . $_GET['name']);
    });

    // Next we have loader, which will dynamically load console which will dynamically output "success" message.
    Loader::addTo($frame)->set(function ($page) {
        Console::addTo($page)->set(function ($console) {
            $console->output('success!, color is still ' . $_GET['name']);
        });
    });
}

$t = Table::addTo($webpage);
$t->setSource(['Red', 'Green', 'Blue']);
$t->addDecorator('name', [Link::class, [], ['name']]);

$frame = View::addTo($webpage, ['ui' => 'green segment']);
Button::addTo($frame, ['does not inherit sticky get'])->on('click', function () {
    return new JsNotify('$_GET = ' . Webpage::encodeJson($_GET));
});

Header::addTo($webpage, ['Use of View::url()']);

$b1 = Button::addTo($webpage);
$b1->set($b1->url());

Loader::addTo($webpage)->set(function ($page) use ($b1) {
    $b2 = Button::addTo($page);
    $b2->set($b2->url());

    $b2->on('click', new JsReload($b1));
});

$b3 = Button::addTo($webpage);
$b3->set($b3->url());
