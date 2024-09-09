<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\Form;
use Phlex\Ui\Form\Control\ScopeBuilder;
use Phlex\Ui\Webpage;

/** @var Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

$model = new Stat($webpage->db, ['caption' => 'Demo Stat']);
$model->addCondition($model->key()->finish_time, '=', '22:12:00');
$model->addCondition($model->key()->start_date, '=', '2020-10-22');

$form = Form::addTo($webpage);

$form->addControl('qb', [ScopeBuilder::class, 'model' => $model, 'options' => ['debug' => true]]);

$form->onSubmit(static function ($form) use ($model) {
    return "Scope selected:\n\n" . $form->model->get('qb')->toWords($model);
});
