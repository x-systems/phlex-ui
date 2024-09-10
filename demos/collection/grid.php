<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\Button;
use Phlex\Ui\Grid;
use Phlex\Ui\Icon;
use Phlex\Ui\Jquery;
use Phlex\Ui\JsExpression;
use Phlex\Ui\JsReload;
use Phlex\Ui\JsToast;
use Phlex\Ui\Message;
use Phlex\Ui\Table\Column\Template;
use Phlex\Ui\UserAction\BasicExecutor;
use Phlex\Ui\Webpage;

/** @var Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

$grid = Grid::addTo($webpage);
$model = new CountryLock($webpage->db);
$model->addUserAction('test', function ($model) {
    return 'test from ' . $model->getTitle() . ' was successful!';
});

$grid->setModel($model);

// Adding Quicksearch on Name field using auto query.
$grid->addQuickSearch([$model->key()->name], true);

if ($webpage->stickyGet('no-ajax')) {
    $grid->quickSearch->useAjax = false;
}

$grid->menu->addItem(['Add Country', 'icon' => 'add square'], new JsExpression('alert(123)'));
$grid->menu->addItem(['Re-Import', 'icon' => 'power'], new JsReload($grid));
$grid->menu->addItem(['Delete All', 'icon' => 'trash', 'red active']);

$grid->addColumn(null, [Template::class, 'hello<b>world</b>']);

// Creating a button for executing model test user action.
$grid->addExecutorButton($grid->getExecutorFactory()->create($model->getUserAction('test'), $grid));

$grid->addActionButton('Say HI', function ($j, $id) use ($grid) {
    $model = Country::assertInstanceOf($grid->model);

    return 'Loaded "' . $model->load($id)->name . '" from ID=' . $id;
});

$grid->addModalAction(['icon' => [Icon::class, 'external']], 'Modal Test', function ($p, $id) {
    Message::addTo($p, ['Clicked on ID=' . $id]);
});

// Creating an executor for delete action.
$deleteExecutor = $grid->getExecutorFactory()->create($model->getUserAction('delete'), $grid);
$deleteExecutor->onHook(BasicExecutor::HOOK_AFTER_EXECUTE, function () {
    return [
        (new Jquery())->closest('tr')->transition('fade left'),
        new JsToast('Simulating delete in demo mode.'),
    ];
});
$grid->addExecutorButton($deleteExecutor, new Button(['icon' => 'times circle outline']));

$sel = $grid->addSelection();
$grid->menu->addItem('show selection')->on('click', new JsExpression(
    'alert("Selected: "+[])',
    [$sel->jsChecked()]
));

// Setting ipp with an array will add an ItemPerPageSelector to paginator.
$grid->setIpp([10, 25, 50, 100]);
