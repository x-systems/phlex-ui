<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\Button;
use Phlex\Ui\Card;
use Phlex\Ui\Columns;
use Phlex\Ui\Crud;
use Phlex\Ui\Grid;
use Phlex\Ui\Header;
use Phlex\Ui\Icon;
use Phlex\Ui\View;
use Phlex\Ui\Webpage;

/** @var Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

Button::addTo($webpage, ['Dynamic scroll in Crud and Grid', 'small left floated basic blue', 'icon' => 'left arrow'])
    ->link(['scroll-grid']);
View::addTo($webpage, ['ui' => 'ui clearing divider']);

Header::addTo($webpage, ['Dynamic scroll in Grid with fixed column headers']);

$c = Columns::addTo($webpage);

$c1 = $c->addColumn();
$g1 = Crud::addTo($c1);
$m1 = $g1->setModel(new CountryLock($webpage->db));
$g1->addQuickSearch([CountryLock::hint()->key()->name, CountryLock::hint()->key()->iso]);

// demo for additional action buttons in Crud + JsPaginator
$g1->addModalAction(['icon' => [Icon::class, 'cogs']], 'Details', function ($p, $id) use ($g1) {
    Card::addTo($p)->setModel($g1->model->load($id));
});
$g1->addActionButton('red', function ($js) {
    return $js->closest('tr')->css('color', 'red');
});
// THIS SHOULD GO AFTER YOU CALL addAction() !!!
$g1->addJsPaginatorInContainer(30, 350);

$c2 = $c->addColumn();
$g2 = Grid::addTo($c2, ['menu' => false]);
$m2 = $g2->setModel(new CountryLock($webpage->db));
$g2->addJsPaginatorInContainer(20, 200);

$g3 = Grid::addTo($c2, ['menu' => false]);
$m3 = $g3->setModel(new CountryLock($webpage->db));
$g3->addJsPaginatorInContainer(10, 150);
