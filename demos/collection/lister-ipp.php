<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\HtmlTemplate;

/** @var \Phlex\Ui\Webpage $webpage */
require_once __DIR__ . '/../init-app.php'; // default lister

\Phlex\Ui\Header::addTo($webpage)->set('Default lister');
\Phlex\Ui\Lister::addTo($webpage, ['defaultTemplate' => 'lister.html'])->setSource([
    ['icon' => 'map marker', 'title' => 'Krolewskie Jadlo', 'descr' => 'An excellent polish restaurant, quick delivery and hearty, filling meals'],
    ['icon' => 'map marker', 'title' => 'Xian Famous Foods', 'descr' => 'A taste of Shaanxi\'s delicious culinary traditions, with delights like spicy cold noodles and lamb burgers.'],
    ['icon' => 'check', 'title' => 'Sapporo Haru', 'descr' => 'Greenpoint\'s best choice for quick and delicious sushi'],
]);
\Phlex\Ui\View::addTo($webpage, ['ui' => 'clearing divider']);

// lister with custom template
$view = \Phlex\Ui\View::addTo($webpage, ['template' => new HtmlTemplate('<div>
<div class="ui header">Top 20 countries (alphabetically)</div>
{List}<div class="ui icon label"><i class="{$atk_fp_country__iso} flag"></i> {$atk_fp_country__name}</div>{/}
</div>')]);

$lister = \Phlex\Ui\Lister::addTo($view, [], ['List']);
$lister->onHook(\Phlex\Ui\Lister::HOOK_BEFORE_ROW, function (\Phlex\Ui\Lister $lister, Country $row) {
    $row->iso = mb_strtolower($row->iso);
});
$lister->setModel(new Country($webpage->db))
    ->setLimit(20);

\Phlex\Ui\View::addTo($webpage, ['ui' => 'clearing divider']);

// empty lister with default template
\Phlex\Ui\Header::addTo($webpage)->set('Empty default lister');
\Phlex\Ui\Lister::addTo($webpage, ['defaultTemplate' => 'lister.html'])->setSource([]);
\Phlex\Ui\View::addTo($webpage, ['ui' => 'clearing divider']);

// empty lister with custom template
$view = \Phlex\Ui\View::addTo($webpage, ['template' => new HtmlTemplate('<div>
<div class="ui header">Empty lister with custom template</div>
{List}<div class="ui icon label"><i class="{$atk_fp_country__iso} flag"></i> {$atk_fp_country__name}</div>{empty}no flags to show here{/}{/}
</div>')]);

$lister = \Phlex\Ui\Lister::addTo($view, [], ['List']);
$lister->onHook(\Phlex\Ui\Lister::HOOK_BEFORE_ROW, function (\Phlex\Ui\Lister $lister, Country $row) {
    $row->iso = mb_strtolower($row->iso);
});
$lister->setModel(new Country($webpage->db))
    ->addCondition(Country::hint()->key()->id, -1); // no such records so model will be empty

\Phlex\Ui\View::addTo($webpage, ['ui' => 'clearing divider']);
\Phlex\Ui\Header::addTo($webpage, ['Item per page', 'subHeader' => 'Lister can display a certain amount of items']);

$container = \Phlex\Ui\View::addTo($webpage);

$view = \Phlex\Ui\View::addTo($container, ['template' => new HtmlTemplate('<div>
<ul>
{List}<li class="ui icon label"><i class="{$atk_fp_country__iso} flag"></i>{$atk_fp_country__name}</li>{/}
</ul>{$Content}</div>')]);

$lister = \Phlex\Ui\Lister::addTo($view, [], ['List']);
$lister->onHook(\Phlex\Ui\Lister::HOOK_BEFORE_ROW, function (\Phlex\Ui\Lister $lister, Country $row) {
    $row->iso = mb_strtolower($row->iso);
});

$model = $lister->setModel(new Country($webpage->db))->setLimit(12);

$ipp = \Phlex\Ui\ItemsPerPageSelector::addTo($view, ['label' => 'Select how many countries:', 'pageLengthItems' => [12, 24, 36]], ['Content']);

$ipp->onPageLengthSelect(function ($ipp) use ($model, $container) {
    $model->setLimit($ipp);

    return $container;
});
