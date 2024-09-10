<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\Grid;
use Phlex\Ui\Header;
use Phlex\Ui\Table;
use Phlex\Ui\Table\Column\Money;
use Phlex\Ui\Text;
use Phlex\Ui\View;
use Phlex\Ui\Webpage;

/** @var Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

Header::addTo($webpage, ['Table column may contains popup or dropdown menu.']);

// Better Popup positionning when Popup are inside a container.
$container = View::addTo($webpage, ['ui' => 'vertical segment']);
$table = Table::addTo($container, ['celled' => true]);
$table->setModel(new SomeData(), false);

// will add popup to this column.
$colName = $table->addColumn('name');

// will add dropdown menu to this colum.
$colSurname = $table->addColumn('surname');

$colTitle = $table->addColumn('title');

$table->addColumn('date');
$table->addColumn('salary', new Money());

// regular popup setup
Text::addTo($colName->addPopup())->set('Name popup');

// dynamic popup setup
// This popup will add content using the callback function.
$colSurname->addPopup()->set(function ($pop) {
    Text::addTo($pop)->set('This popup is loaded dynamically');
});

// Another dropdown menu.
$colTitle->addDropdown(['Change', 'Reorder', 'Update'], function ($item) {
    return 'Title item: ' . $item;
});

// //////////////////////////////////////////////

Header::addTo($webpage, ['Grid column may contains popup or dropdown menu.']);

// Table in Grid are already inside a container.
$grid = Grid::addTo($webpage);
$grid->setModel(new Country($webpage->db));
$grid->ipp = 5;

// Adding a dropdown menu to the column 'name'.
$grid->addDropdown(Country::hint()->key()->name, ['Rename', 'Delete'], function ($item) {
    return $item;
});

// Adding a popup view to the column 'iso'
$pop = $grid->addPopup(Country::hint()->key()->iso);
Text::addTo($pop)->set('Grid column popup');
