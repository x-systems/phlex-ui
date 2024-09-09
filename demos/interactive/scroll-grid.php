<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\Button;
use Phlex\Ui\Grid;
use Phlex\Ui\Header;
use Phlex\Ui\View;
use Phlex\Ui\Webpage;

/** @var Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

Button::addTo($webpage, ['Dynamic scroll in Container', 'small left floated basic blue', 'icon' => 'left arrow'])
    ->link(['scroll-container']);
Button::addTo($webpage, ['Dynamic scroll in Grid using Container', 'small right floated basic blue', 'iconRight' => 'right arrow'])
    ->link(['scroll-grid-container']);
View::addTo($webpage, ['ui' => 'ui clearing divider']);

Header::addTo($webpage, ['Dynamic scroll in Grid']);

$grid = Grid::addTo($webpage, ['menu' => false]);
$model = $grid->setModel(new CountryLock($webpage->db));

$grid->addJsPaginator(30);
