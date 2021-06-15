<?php

declare(strict_types=1);
/**
 * For Behat testing only.
 * Will test for Add, Edit and delete button using quicksearch.
 * see crud.feature.
 */

namespace Phlex\Ui\Demos;

use Phlex\Ui\UserAction\ExecutorFactory;

/** @var \Phlex\Ui\App $app */
require_once __DIR__ . '/../init-app.php';

// reset to default button
$app->getExecutorFactory()->useTriggerDefault(ExecutorFactory::TABLE_BUTTON);

$model = new CountryLock($app->db);
$crud = \Phlex\Ui\Crud::addTo($app, ['ipp' => 10, 'menu' => ['class' => ['atk-grid-menu']]]);
$crud->setModel($model);

$crud->addQuickSearch([$model->fieldName()->name], true);
