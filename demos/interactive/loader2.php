<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\Button;
use Phlex\Ui\Columns;
use Phlex\Ui\Form;
use Phlex\Ui\Grid;
use Phlex\Ui\Loader;
use Phlex\Ui\Text;
use Phlex\Ui\View;
use Phlex\Ui\Webpage;

/** @var Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

Button::addTo($webpage, ['Loader Example - page 1', 'small left floated basic blue', 'icon' => 'left arrow'])
    ->link(['loader']);
View::addTo($webpage, ['ui' => 'ui clearing divider']);

$c = Columns::addTo($webpage);

$grid = Grid::addTo($c->addColumn(), ['ipp' => 10, 'menu' => false]);
$grid->setModel(new Country($webpage->db), [Country::hint()->key()->name]);

$countryLoader = Loader::addTo($c->addColumn(), ['loadEvent' => false, 'shim' => [Text::class, 'Select country on your left']]);

$grid->table->onRowClick($countryLoader->jsLoad(['id' => $grid->table->jsRow()->data('id')]));

$countryLoader->set(static function ($p) {
    Form::addTo($p)->setModel((new Country($p->getApp()->db))->load($_GET['id']));
});
