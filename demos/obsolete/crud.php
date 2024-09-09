<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\Crud;
use Phlex\Ui\Table\Column\Link;
use Phlex\Ui\Webpage;

/** @var Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

$model = new Stat($webpage->db);
$model->getUserAction('add')->system = true;
$model->getUserAction('edit')->system = true;
$model->getUserAction('delete')->system = true;

$grid = Crud::addTo($webpage, ['paginator' => false]);
$grid->setModel($model);
$grid->addDecorator($model->key()->project_code, [Link::class]);
