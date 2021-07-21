<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\Form;
use Phlex\Ui\JsToast;

/** @var \Phlex\Ui\App $app */
require_once __DIR__ . '/../init-app.php';

\Phlex\Ui\View::addTo($app, [
    'Forms below demonstrate how to work with multi-value selectors',
    'ui' => 'ignored warning message',
]);

$cc = \Phlex\Ui\Columns::addTo($app);
$form = Form::addTo($cc->addColumn());

$form->addControl('one', null, ['type' => ['enum', 'values' => ['female', 'male']]])->set('male');
$form->addControl('two', [Form\Control\Radio::class], ['type' => ['enum', 'values' => ['female', 'male']]])->set('male');

$form->addControl('three', null, ['type' => ['enum', 'valuesWithLabels' => ['female', 'male']]])->set(1);
$form->addControl('four', [Form\Control\Radio::class], ['type' => ['enum', 'valuesWithLabels' => ['female', 'male']]])->set(1);

$form->addControl('five', null, ['type' => ['enum', 'values' => [5 => 'female', 7 => 'male']]])->set(7);
$form->addControl('six', [Form\Control\Radio::class], ['type' => ['enum', 'values' => [5 => 'female', 7 => 'male']]])->set(7);

$form->addControl('seven', null, ['type' => ['enum', 'values' => ['F' => 'female', 'M' => 'male']]])->set('M');
$form->addControl('eight', [Form\Control\Radio::class], ['type' => ['enum', 'values' => ['F' => 'female', 'M' => 'male']]])->set('M');

$form->onSubmit(function (Form $form) use ($app) {
    return new JsToast($app->encodeJson($form->model->get()));
});
