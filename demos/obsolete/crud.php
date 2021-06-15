<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

/** @var \Phlex\Ui\App $app */
require_once __DIR__ . '/../init-app.php';

$model = new Stat($app->db);
$model->getUserAction('add')->system = true;
$model->getUserAction('edit')->system = true;
$model->getUserAction('delete')->system = true;

$grid = \Phlex\Ui\Crud::addTo($app, ['paginator' => false]);
$grid->setModel($model);
$grid->addDecorator($model->fieldName()->project_code, [\Phlex\Ui\Table\Column\Link::class]);
