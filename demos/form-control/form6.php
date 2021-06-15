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

$form->addControl('one', null, ['enum' => ['female', 'male']])->set('male');
$form->addControl('two', [Form\Control\Radio::class], ['enum' => ['female', 'male']])->set('male');

$form->addControl('three', null, ['values' => ['female', 'male']])->set(1);
$form->addControl('four', [Form\Control\Radio::class], ['values' => ['female', 'male']])->set(1);

$form->addControl('five', null, ['values' => [5 => 'female', 7 => 'male']])->set(7);
$form->addControl('six', [Form\Control\Radio::class], ['values' => [5 => 'female', 7 => 'male']])->set(7);

$form->addControl('seven', null, ['values' => ['F' => 'female', 'M' => 'male']])->set('M');
$form->addControl('eight', [Form\Control\Radio::class], ['values' => ['F' => 'female', 'M' => 'male']])->set('M');

$form->onSubmit(function (Form $form) use ($app) {
    return new JsToast($app->encodeJson($form->model->get()));
});
