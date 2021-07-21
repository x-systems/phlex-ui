<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\Crud;
use Phlex\Ui\UserAction\ExecutorFactory;

// Test for hasOne Lookup as dropdown control.

/** @var \Phlex\Ui\Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

$model = new ProductLock($webpage->db);
$model->addCondition($model->key()->name, '=', 'Mustard');

// use default.
$webpage->getExecutorFactory()->useTriggerDefault(ExecutorFactory::TABLE_BUTTON);

$edit = $model->getUserAction('edit');
$edit->callback = function ($model) {
    return $model->product_category_id->getTitle() . ' - ' . $model->product_sub_category_id->getTitle();
};

$crud = Crud::addTo($webpage);
$crud->setModel($model, [$model->key()->name]);
