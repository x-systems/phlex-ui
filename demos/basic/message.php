<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\Button;
use Phlex\Ui\Header;
use Phlex\Ui\Jquery;
use Phlex\Ui\JsReload;
use Phlex\Ui\Message;
use Phlex\Ui\View;
use Phlex\Ui\Webpage;

/** @var Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

$img = 'https://github.com/atk4/ui/raw/07208a0af84109f0d6e3553e242720d8aeedb784/public/logo.png';

Header::addTo($webpage, ['Message Types']);

$seg = View::addTo($webpage, ['ui' => 'raised segment']);

$barType = View::addTo($seg, ['ui' => ' basic buttons']);

$msg = Message::addTo($seg, [
    'This is a title of your message',
    'type' => $webpage->stickyGet('type'),
    'icon' => $webpage->stickyGet('icon'),
]);
$msg->text->addParagraph('You can add some more text here for your messages');

$barType->on('click', '.button', new JsReload($seg, ['type' => (new Jquery())->text()]));
Button::addTo($barType, ['success']);
Button::addTo($barType, ['error']);
Button::addTo($barType, ['info']);
Button::addTo($barType, ['warning']);

$barIcon = View::addTo($seg, ['ui' => ' basic buttons']);
$barIcon->on('click', '.button', new JsReload($seg, ['icon' => (new Jquery())->find('i')->attr('class')]));
Button::addTo($barIcon, ['icon' => 'book']);
Button::addTo($barIcon, ['icon' => 'check circle outline']);
Button::addTo($barIcon, ['icon' => 'pointing right']);
Button::addTo($barIcon, ['icon' => 'asterisk loading']);
Button::addTo($barIcon, ['icon' => 'vertically flipped cloud']);
