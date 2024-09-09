<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\Grid;
use Phlex\Ui\View;
use Phlex\Ui\Webpage;

/** @var Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

// For popup positioning to work correctly, table need to be inside a view segment.
$view = View::addTo($webpage, ['ui' => 'basic segment']);
// Important: menu class added for Behat testing.
$grid = Grid::addTo($view, ['menu' => ['class' => ['phlex-grid-menu']]]);

$model = new CountryLock($webpage->db);
$model->addExpression('is_uk', $model->expr('case when [phlex_fp_country__iso] = [country] THEN 1 ELSE 0 END', ['country' => 'GB']))->type = 'boolean';

$grid->setModel($model);
$grid->addFilterColumn();

$grid->ipp = 20;
