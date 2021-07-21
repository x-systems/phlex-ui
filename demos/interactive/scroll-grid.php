<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

/** @var \Phlex\Ui\Webpage $app */
require_once __DIR__ . '/../init-app.php';

\Phlex\Ui\Button::addTo($app, ['Dynamic scroll in Container', 'small left floated basic blue', 'icon' => 'left arrow'])
    ->link(['scroll-container']);
\Phlex\Ui\Button::addTo($app, ['Dynamic scroll in Grid using Container', 'small right floated basic blue', 'iconRight' => 'right arrow'])
    ->link(['scroll-grid-container']);
\Phlex\Ui\View::addTo($app, ['ui' => 'ui clearing divider']);

\Phlex\Ui\Header::addTo($app, ['Dynamic scroll in Grid']);

$grid = \Phlex\Ui\Grid::addTo($app, ['menu' => false]);
$model = $grid->setModel(new CountryLock($app->db));

$grid->addJsPaginator(30);
