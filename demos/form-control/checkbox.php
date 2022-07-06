<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\Form;
use Phlex\Ui\View;
use Phlex\Ui\Webpage;

/** @var \Phlex\Ui\Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

// Testing fields.

\Phlex\Ui\Header::addTo($webpage, ['CheckBoxes', 'size' => 2]);

Form\Control\Checkbox::addTo($webpage, ['Make my profile visible']);
Form\Control\Checkbox::addTo($webpage, ['Make my profile visible ticked'])->set(true);

View::addTo($webpage, ['ui' => 'divider']);
Form\Control\Checkbox::addTo($webpage, ['Accept terms and conditions', 'slider']);

View::addTo($webpage, ['ui' => 'divider']);
Form\Control\Checkbox::addTo($webpage, ['Subscribe to weekly newsletter', 'toggle']);
View::addTo($webpage, ['ui' => 'divider']);
Form\Control\Checkbox::addTo($webpage, ['Look for the clues', 'disabled toggle'])->set(true);

View::addTo($webpage, ['ui' => 'divider']);
Form\Control\Checkbox::addTo($webpage, ['Custom setting?'])->js(true)->checkbox('set indeterminate');

\Phlex\Ui\Header::addTo($webpage, ['CheckBoxes in a form', 'size' => 2]);
$form = Form::addTo($webpage);
$form->addControl('test', [Form\Control\Checkbox::class]);
$form->addControl('test_checked', [Form\Control\Checkbox::class])->set(true);
$form->addControl('also_checked', 'Hello World', 'boolean')->set(true);

$form->onSubmit(function ($f) {
    return new \Phlex\Ui\JsToast(Webpage::encodeJson($f->model->get()));
});

View::addTo($webpage, ['ui' => 'divider']);
$c = new Form\Control\Checkbox('Selected checkbox by default');
$c->set(true);
$webpage->addView($c);
