<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

/** @var \Phlex\Ui\Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

// Test 1 - Basic reloading
\Phlex\Ui\Header::addTo($webpage, ['Button reloading segment']);
$v = \Phlex\Ui\View::addTo($webpage, ['ui' => 'segment'])->set((string) random_int(1, 100));
\Phlex\Ui\Button::addTo($webpage, ['Reload random number'])->js('click', new \Phlex\Ui\JsReload($v, [], new \Phlex\Ui\JsExpression('console.log("Output with afterSuccess");')));

// Test 2 - Reloading self
\Phlex\Ui\Header::addTo($webpage, ['JS-actions will be re-applied']);
$b2 = \Phlex\Ui\Button::addTo($webpage, ['Reload Myself']);
$b2->js('click', new \Phlex\Ui\JsReload($b2));

// Test 3 - avoid duplicate
\Phlex\Ui\Header::addTo($webpage, ['No duplicate JS bindings']);
$b3 = \Phlex\Ui\Button::addTo($webpage, ['Reload other button']);
$b4 = \Phlex\Ui\Button::addTo($webpage, ['Add one dot']);

$b4->js('click', $b4->js()->text(new \Phlex\Ui\JsExpression('[]+"."', [$b4->js()->text()])));
$b3->js('click', new \Phlex\Ui\JsReload($b4));

// Test 3 - avoid duplicate
\Phlex\Ui\Header::addTo($webpage, ['Make sure nested JS bindings are applied too']);
$seg = \Phlex\Ui\View::addTo($webpage, ['ui' => 'segment']);

// add 3 counters
Counter::addTo($seg);
Counter::addTo($seg, ['40']);
Counter::addTo($seg, ['-20']);

// Add button to reload all counters
$bar = \Phlex\Ui\View::addTo($webpage, ['ui' => 'buttons']);
$b = \Phlex\Ui\Button::addTo($bar, ['Reload counter'])->js('click', new \Phlex\Ui\JsReload($seg));

// Relading with argument
\Phlex\Ui\Header::addTo($webpage, ['We can pass argument to reloader']);

$v = \Phlex\Ui\View::addTo($webpage, ['ui' => 'segment'])->set($_GET['val'] ?? 'No value');

\Phlex\Ui\Button::addTo($webpage, ['Set value to "hello"'])->js('click', new \Phlex\Ui\JsReload($v, ['val' => 'hello']));
\Phlex\Ui\Button::addTo($webpage, ['Set value to "world"'])->js('click', new \Phlex\Ui\JsReload($v, ['val' => 'world']));

$val = \Phlex\Ui\Form\Control\Line::addTo($webpage, ['']);
$val->addAction('Set Custom Value')->js('click', new \Phlex\Ui\JsReload($v, ['val' => $val->jsInput()->val()], $val->jsInput()->focus()));
