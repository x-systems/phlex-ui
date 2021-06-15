<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

/** @var \Phlex\Ui\App $app */
require_once __DIR__ . '/../init-app.php';

$img = 'https://github.com/atk4/ui/raw/07208a0af84109f0d6e3553e242720d8aeedb784/public/logo.png';

\Phlex\Ui\Header::addTo($app, ['Message Types']);

$seg = \Phlex\Ui\View::addTo($app, ['ui' => 'raised segment']);

$barType = \Phlex\Ui\View::addTo($seg, ['ui' => ' basic buttons']);

$msg = \Phlex\Ui\Message::addTo($seg, [
    'This is a title of your message',
    'type' => $app->stickyGet('type'),
    'icon' => $app->stickyGet('icon'),
]);
$msg->text->addParagraph('You can add some more text here for your messages');

$barType->on('click', '.button', new \Phlex\Ui\JsReload($seg, ['type' => (new \Phlex\Ui\Jquery())->text()]));
\Phlex\Ui\Button::addTo($barType, ['success']);
\Phlex\Ui\Button::addTo($barType, ['error']);
\Phlex\Ui\Button::addTo($barType, ['info']);
\Phlex\Ui\Button::addTo($barType, ['warning']);

$barIcon = \Phlex\Ui\View::addTo($seg, ['ui' => ' basic buttons']);
$barIcon->on('click', '.button', new \Phlex\Ui\JsReload($seg, ['icon' => (new \Phlex\Ui\Jquery())->find('i')->attr('class')]));
\Phlex\Ui\Button::addTo($barIcon, ['icon' => 'book']);
\Phlex\Ui\Button::addTo($barIcon, ['icon' => 'check circle outline']);
\Phlex\Ui\Button::addTo($barIcon, ['icon' => 'pointing right']);
\Phlex\Ui\Button::addTo($barIcon, ['icon' => 'asterisk loading']);
\Phlex\Ui\Button::addTo($barIcon, ['icon' => 'vertically flipped cloud']);
