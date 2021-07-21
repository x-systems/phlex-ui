<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

/** @var \Phlex\Ui\Webpage $app */
require_once __DIR__ . '/../init-app.php';

\Phlex\Ui\Button::addTo($app, ['Loader Example - page 1', 'small left floated basic blue', 'icon' => 'left arrow'])
    ->link(['loader']);
\Phlex\Ui\View::addTo($app, ['ui' => 'ui clearing divider']);

$c = \Phlex\Ui\Columns::addTo($app);

$grid = \Phlex\Ui\Grid::addTo($c->addColumn(), ['ipp' => 10, 'menu' => false]);
$grid->setModel(new Country($app->db), [Country::hint()->key()->name]);

$countryLoader = \Phlex\Ui\Loader::addTo($c->addColumn(), ['loadEvent' => false, 'shim' => [\Phlex\Ui\Text::class, 'Select country on your left']]);

$grid->table->onRowClick($countryLoader->jsLoad(['id' => $grid->table->jsRow()->data('id')]));

$countryLoader->set(function ($p) {
    \Phlex\Ui\Form::addTo($p)->setModel((new Country($p->getApp()->db))->load($_GET['id']));
});
