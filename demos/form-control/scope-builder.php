<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

/** @var \Phlex\Ui\Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

$model = new Stat($webpage->db, ['caption' => 'Demo Stat']);
$model->addCondition($model->key()->finish_time, '=', '22:12:00');
$model->addCondition($model->key()->start_date, '=', '2020-10-22');

$form = \Phlex\Ui\Form::addTo($webpage);

$form->addControl('qb', [\Phlex\Ui\Form\Control\ScopeBuilder::class, 'model' => $model, 'options' => ['debug' => true]]);

$form->onSubmit(function ($form) use ($model) {
    return "Scope selected:\n\n" . $form->model->get('qb')->toWords($model);
});
