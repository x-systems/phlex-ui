<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\Crud;
use Phlex\Ui\UserAction\ExecutorFactory;

// Test for hasOne Lookup as dropdown control.

/** @var \Phlex\Ui\App $app */
require_once __DIR__ . '/../init-app.php';

$model = new ProductLock($app->db);
$model->addCondition($model->fieldName()->name, '=', 'Mustard');

// use default.
$app->getExecutorFactory()->useTriggerDefault(ExecutorFactory::TABLE_BUTTON);

$edit = $model->getUserAction('edit');
$edit->callback = function ($model) {
    return $model->product_category_id->getTitle() . ' - ' . $model->product_sub_category_id->getTitle();
};

$crud = Crud::addTo($app);
$crud->setModel($model, [$model->fieldName()->name]);
