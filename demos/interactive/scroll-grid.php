<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

/** @var \Phlex\Ui\Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

\Phlex\Ui\Button::addTo($webpage, ['Dynamic scroll in Container', 'small left floated basic blue', 'icon' => 'left arrow'])
    ->link(['scroll-container']);
\Phlex\Ui\Button::addTo($webpage, ['Dynamic scroll in Grid using Container', 'small right floated basic blue', 'iconRight' => 'right arrow'])
    ->link(['scroll-grid-container']);
\Phlex\Ui\View::addTo($webpage, ['ui' => 'ui clearing divider']);

\Phlex\Ui\Header::addTo($webpage, ['Dynamic scroll in Grid']);

$grid = \Phlex\Ui\Grid::addTo($webpage, ['menu' => false]);
$model = $grid->setModel(new CountryLock($webpage->db));

$grid->addJsPaginator(30);
