<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\Button;
use Phlex\Ui\Header;

/** @var \Phlex\Ui\Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

// Demonstrates how to use interractive buttons.
Header::addTo($webpage, ['Basic Button']);

// This button hides on page load
$b = Button::addTo($webpage, ['Hidden Button']);
$b->js(true)->hide();

// This button hides when clicked
$b = Button::addTo($webpage, ['id' => 'b2'])->set('Hide on click Button');
$b->js('click')->hide();

Button::addTo($webpage, ['Redirect'])->on('click', null, $webpage->jsRedirect(['foo' => 'bar']));

if (isset($_GET['foo']) && $_GET['foo'] === 'bar') {
    $webpage->redirect(['foo' => 'baz']);
}

Header::addTo($webpage, ['js() method']);

$b = Button::addTo($webpage, ['Hide button B']);
$b2 = Button::addTo($webpage, ['B']);
$b->js('click', $b2->js()->hide('b2'))->hide('b1');

Header::addTo($webpage, ['on() method']);

$b = Button::addTo($webpage, ['Hide button C']);
$b2 = Button::addTo($webpage, ['C']);
$b->on('click', null, $b2->js()->hide('c2'))->hide('c1');

Header::addTo($webpage, ['Callbacks']);

// On button click reload it and change it's title
$b = Button::addTo($webpage, ['Callback Test']);
$b->on('click', null, function ($b) {
    return $b->text(random_int(1, 20));
});

$b = Button::addTo($webpage, ['success']);
$b->on('click', null, function ($b) {
    return 'success';
});

$b = Button::addTo($webpage, ['failure']);
$b->on('click', null, function ($b) {
    throw new \Phlex\Data\Model\Field\ValidationException(['Everything is bad']);
});

Header::addTo($webpage, ['Callbacks on HTML element', 'subHeader' => 'Click on label below.']);

$label = \Phlex\Ui\Label::addTo($webpage->layout, ['Test']);

$label->on('click', null, function ($j, $arg1) {
    return 'width is ' . $arg1;
}, [new \Phlex\Ui\JsExpression('$(window).width()')]);
