<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\Button;
use Phlex\Ui\Header;
use Phlex\Ui\HtmlTemplate;
use Phlex\Ui\Lister;
use Phlex\Ui\View;
use Phlex\Ui\Webpage;

/** @var Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

Button::addTo($webpage, ['Dynamic scroll in Table', 'small right floated basic blue', 'iconRight' => 'right arrow'])
    ->link(['scroll-table']);
View::addTo($webpage, ['ui' => 'ui clearing divider']);

Header::addTo($webpage, ['Dynamic scroll in Lister']);

$container = View::addTo($webpage);

$view = View::addTo($container, ['template' => new HtmlTemplate('
{List}<div class="ui segment" style="height: 60px"><i class="{$phlex_fp_country__iso} flag"></i> {$phlex_fp_country__name}</div>{/}
{$Content}')]);

$lister = Lister::addTo($view, [], ['List']);
$lister->onHook(Lister::HOOK_BEFORE_ROW, function (Lister $lister, Country $row) {
    $row->iso = mb_strtolower($row->iso);
});

$model = $lister->setModel(new Country($webpage->db));
// $model->addCondition(Country::hint()->key()->name, 'like', 'A%');

// add dynamic scrolling.
$lister->addJsPaginator(30, [], $container);
