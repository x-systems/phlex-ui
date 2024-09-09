<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\Button;
use Phlex\Ui\Form\Control\Line;
use Phlex\Ui\Header;
use Phlex\Ui\JsExpression;
use Phlex\Ui\JsReload;
use Phlex\Ui\View;
use Phlex\Ui\Webpage;

/** @var Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

// Test 1 - Basic reloading
Header::addTo($webpage, ['Button reloading segment']);
$v = View::addTo($webpage, ['ui' => 'segment'])->set((string) random_int(1, 100));
Button::addTo($webpage, ['Reload random number'])->js('click', new JsReload($v, [], new JsExpression('console.log("Output with afterSuccess");')));

// Test 2 - Reloading self
Header::addTo($webpage, ['JS-actions will be re-applied']);
$b2 = Button::addTo($webpage, ['Reload Myself']);
$b2->js('click', new JsReload($b2));

// Test 3 - avoid duplicate
Header::addTo($webpage, ['No duplicate JS bindings']);
$b3 = Button::addTo($webpage, ['Reload other button']);
$b4 = Button::addTo($webpage, ['Add one dot']);

$b4->js('click', $b4->js()->text(new JsExpression('[]+"."', [$b4->js()->text()])));
$b3->js('click', new JsReload($b4));

// Test 3 - avoid duplicate
Header::addTo($webpage, ['Make sure nested JS bindings are applied too']);
$seg = View::addTo($webpage, ['ui' => 'segment']);

// add 3 counters
Counter::addTo($seg);
Counter::addTo($seg, ['40']);
Counter::addTo($seg, ['-20']);

// Add button to reload all counters
$bar = View::addTo($webpage, ['ui' => 'buttons']);
$b = Button::addTo($bar, ['Reload counter'])->js('click', new JsReload($seg));

// Relading with argument
Header::addTo($webpage, ['We can pass argument to reloader']);

$v = View::addTo($webpage, ['ui' => 'segment'])->set($_GET['val'] ?? 'No value');

Button::addTo($webpage, ['Set value to "hello"'])->js('click', new JsReload($v, ['val' => 'hello']));
Button::addTo($webpage, ['Set value to "world"'])->js('click', new JsReload($v, ['val' => 'world']));

$val = Line::addTo($webpage, ['']);
$val->addAction('Set Custom Value')->js('click', new JsReload($v, ['val' => $val->jsInput()->val()], $val->jsInput()->focus()));
