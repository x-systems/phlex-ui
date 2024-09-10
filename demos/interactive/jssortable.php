<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\Button;
use Phlex\Ui\Grid;
use Phlex\Ui\Header;
use Phlex\Ui\HtmlTemplate;
use Phlex\Ui\JsSortable;
use Phlex\Ui\JsToast;
use Phlex\Ui\Lister;
use Phlex\Ui\View;
use Phlex\Ui\Webpage;

/** @var Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

$view = View::addTo($webpage, ['template' => new HtmlTemplate(
    '<div class="ui header">Click and drag country to reorder</div>
    <div id="{$_id}" style="cursor: pointer">
        <ul>
            {List}<li class="ui icon label" data-name="{$phlex_fp_country__name}"><i class="{$phlex_fp_country__iso} flag"></i> {$phlex_fp_country__name}</li>{/}
        </ul>
    </div>'
)]);

$lister = Lister::addTo($view, [], ['List']);
$lister->onHook(Lister::HOOK_BEFORE_ROW, function (Lister $lister, Country $row) {
    $row->iso = mb_strtolower($row->iso);
});
$lister->setModel(new Country($webpage->db))
    ->setLimit(20);

$sortable = JsSortable::addTo($view, ['container' => 'ul', 'draggable' => 'li', 'dataLabel' => 'name']);

$sortable->onReorder(function ($order, $src, $pos, $oldPos) {
    if ($_GET['btn'] ?? null) {
        return new JsToast(implode(' - ', $order));
    }

    return new JsToast($src . ' moved from position ' . $oldPos . ' to ' . $pos);
});

$button = Button::addTo($webpage)->set('Get countries order');
$button->js('click', $sortable->jsGetOrders(['btn' => '1']));

// ////////////////////////////////////////////////////////////////////////////////////////
View::addTo($webpage, ['ui' => 'divider']);
Header::addTo($webpage, ['Add Drag n drop to Grid']);

$grid = Grid::addTo($webpage, ['paginator' => false]);
$grid->setModel((new Country($webpage->db))->setLimit(6));

$dragHandler = $grid->addDragHandler();
$dragHandler->onReorder(function ($order) {
    return new JsToast('New order: ' . implode(' - ', $order));
});
