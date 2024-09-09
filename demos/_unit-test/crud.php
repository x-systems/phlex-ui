<?php

declare(strict_types=1);
/**
 * For Behat testing only.
 * Will test for Add, Edit and delete button using quicksearch.
 * see crud.feature.
 */

namespace Phlex\Ui\Demos;

use Phlex\Ui\Crud;
use Phlex\Ui\UserAction\ExecutorFactory;
use Phlex\Ui\Webpage;

/** @var Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

// reset to default button
$webpage->getExecutorFactory()->useTriggerDefault(ExecutorFactory::TABLE_BUTTON);

$model = new CountryLock($webpage->db);
$crud = Crud::addTo($webpage, ['ipp' => 10, 'menu' => ['class' => ['phlex-grid-menu']]]);
$crud->setModel($model);

$crud->addQuickSearch([$model->key()->name], true);
