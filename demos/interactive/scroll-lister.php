<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\HtmlTemplate;

/** @var \Phlex\Ui\App $app */
require_once __DIR__ . '/../init-app.php';

\Phlex\Ui\Button::addTo($app, ['Dynamic scroll in Table', 'small right floated basic blue', 'iconRight' => 'right arrow'])
    ->link(['scroll-table']);
\Phlex\Ui\View::addTo($app, ['ui' => 'ui clearing divider']);

\Phlex\Ui\Header::addTo($app, ['Dynamic scroll in Lister']);

$container = \Phlex\Ui\View::addTo($app);

$view = \Phlex\Ui\View::addTo($container, ['template' => new HtmlTemplate('
{List}<div class="ui segment" style="height: 60px"><i class="{$atk_fp_country__iso} flag"></i> {$atk_fp_country__name}</div>{/}
{$Content}')]);

$lister = \Phlex\Ui\Lister::addTo($view, [], ['List']);
$lister->onHook(\Phlex\Ui\Lister::HOOK_BEFORE_ROW, function (\Phlex\Ui\Lister $lister, Country $row) {
    $row->iso = mb_strtolower($row->iso);
});

$model = $lister->setModel(new Country($app->db));
//$model->addCondition(Country::hint()->key()->name, 'like', 'A%');

// add dynamic scrolling.
$lister->addJsPaginator(30, [], $container);
