<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\Header;
use Phlex\Ui\HtmlTemplate;
use Phlex\Ui\ItemsPerPageSelector;
use Phlex\Ui\Lister;
use Phlex\Ui\View;
use Phlex\Ui\Webpage;

/** @var Webpage $webpage */
require_once __DIR__ . '/../init-app.php'; // default lister

Header::addTo($webpage)->set('Default lister');
Lister::addTo($webpage, ['defaultTemplate' => 'lister.html'])->setSource([
    ['icon' => 'map marker', 'title' => 'Krolewskie Jadlo', 'descr' => 'An excellent polish restaurant, quick delivery and hearty, filling meals'],
    ['icon' => 'map marker', 'title' => 'Xian Famous Foods', 'descr' => 'A taste of Shaanxi\'s delicious culinary traditions, with delights like spicy cold noodles and lamb burgers.'],
    ['icon' => 'check', 'title' => 'Sapporo Haru', 'descr' => 'Greenpoint\'s best choice for quick and delicious sushi'],
]);
View::addTo($webpage, ['ui' => 'clearing divider']);

// lister with custom template
$view = View::addTo($webpage, ['template' => new HtmlTemplate('<div>
<div class="ui header">Top 20 countries (alphabetically)</div>
{List}<div class="ui icon label"><i class="{$phlex_fp_country__iso} flag"></i> {$phlex_fp_country__name}</div>{/}
</div>')]);

$lister = Lister::addTo($view, [], ['List']);
$lister->onHook(Lister::HOOK_BEFORE_ROW, static function (Lister $lister, Country $row) {
    $row->iso = mb_strtolower($row->iso);
});
$lister->setModel(new Country($webpage->db))
    ->setLimit(20);

View::addTo($webpage, ['ui' => 'clearing divider']);

// empty lister with default template
Header::addTo($webpage)->set('Empty default lister');
Lister::addTo($webpage, ['defaultTemplate' => 'lister.html'])->setSource([]);
View::addTo($webpage, ['ui' => 'clearing divider']);

// empty lister with custom template
$view = View::addTo($webpage, ['template' => new HtmlTemplate('<div>
<div class="ui header">Empty lister with custom template</div>
{List}<div class="ui icon label"><i class="{$phlex_fp_country__iso} flag"></i> {$phlex_fp_country__name}</div>{empty}no flags to show here{/}{/}
</div>')]);

$lister = Lister::addTo($view, [], ['List']);
$lister->onHook(Lister::HOOK_BEFORE_ROW, static function (Lister $lister, Country $row) {
    $row->iso = mb_strtolower($row->iso);
});
$lister->setModel(new Country($webpage->db))
    ->addCondition(Country::hint()->key()->id, -1); // no such records so model will be empty

View::addTo($webpage, ['ui' => 'clearing divider']);
Header::addTo($webpage, ['Item per page', 'subHeader' => 'Lister can display a certain amount of items']);

$container = View::addTo($webpage);

$view = View::addTo($container, ['template' => new HtmlTemplate('<div>
<ul>
{List}<li class="ui icon label"><i class="{$phlex_fp_country__iso} flag"></i>{$phlex_fp_country__name}</li>{/}
</ul>{$Content}</div>')]);

$lister = Lister::addTo($view, [], ['List']);
$lister->onHook(Lister::HOOK_BEFORE_ROW, static function (Lister $lister, Country $row) {
    $row->iso = mb_strtolower($row->iso);
});

$model = $lister->setModel(new Country($webpage->db))->setLimit(12);

$ipp = ItemsPerPageSelector::addTo($view, ['label' => 'Select how many countries:', 'pageLengthItems' => [12, 24, 36]], ['Content']);

$ipp->onPageLengthSelect(static function ($ipp) use ($model, $container) {
    $model->setLimit($ipp);

    return $container;
});
