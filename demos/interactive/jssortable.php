<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\HtmlTemplate;

/** @var \Phlex\Ui\App $app */
require_once __DIR__ . '/../init-app.php';

$view = \Phlex\Ui\View::addTo($app, ['template' => new HtmlTemplate(
    '<div class="ui header">Click and drag country to reorder</div>
    <div id="{$_id}" style="cursor: pointer">
        <ul>
            {List}<li class="ui icon label" data-name="{$atk_fp_country__name}"><i class="{$atk_fp_country__iso} flag"></i> {$atk_fp_country__name}</li>{/}
        </ul>
    </div>'
)]);

$lister = \Phlex\Ui\Lister::addTo($view, [], ['List']);
$lister->onHook(\Phlex\Ui\Lister::HOOK_BEFORE_ROW, function (\Phlex\Ui\Lister $lister, Country $row) {
    $row->iso = mb_strtolower($row->iso);
});
$lister->setModel(new Country($app->db))
    ->setLimit(20);

$sortable = \Phlex\Ui\JsSortable::addTo($view, ['container' => 'ul', 'draggable' => 'li', 'dataLabel' => 'name']);

$sortable->onReorder(function ($order, $src, $pos, $oldPos) {
    if ($_GET['btn'] ?? null) {
        return new \Phlex\Ui\JsToast(implode(' - ', $order));
    }

    return new \Phlex\Ui\JsToast($src . ' moved from position ' . $oldPos . ' to ' . $pos);
});

$button = \Phlex\Ui\Button::addTo($app)->set('Get countries order');
$button->js('click', $sortable->jsGetOrders(['btn' => '1']));

//////////////////////////////////////////////////////////////////////////////////////////
\Phlex\Ui\View::addTo($app, ['ui' => 'divider']);
\Phlex\Ui\Header::addTo($app, ['Add Drag n drop to Grid']);

$grid = \Phlex\Ui\Grid::addTo($app, ['paginator' => false]);
$grid->setModel((new Country($app->db))->setLimit(6));

$dragHandler = $grid->addDragHandler();
$dragHandler->onReorder(function ($order) {
    return new \Phlex\Ui\JsToast('New order: ' . implode(' - ', $order));
});
