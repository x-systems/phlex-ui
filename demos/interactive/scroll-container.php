<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\HtmlTemplate;

/** @var \Phlex\Ui\Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

\Phlex\Ui\Button::addTo($webpage, ['Dynamic scroll in Table', 'small left floated basic blue', 'icon' => 'left arrow'])
    ->link(['scroll-table']);
\Phlex\Ui\Button::addTo($webpage, ['Dynamic scroll in Grid', 'small right floated basic blue', 'iconRight' => 'right arrow'])
    ->link(['scroll-grid']);
\Phlex\Ui\View::addTo($webpage, ['ui' => 'ui clearing divider']);

\Phlex\Ui\Header::addTo($webpage, ['Dynamic scroll in Container']);

$view = \Phlex\Ui\View::addTo($webpage)->addClass('ui basic segment atk-scroller');

$scrollContainer = \Phlex\Ui\View::addTo($view)->addClass('ui segment')->addStyle(['max-height' => '400px', 'overflow-y' => 'scroll']);

$listerTemplate = '<div id="{$_id}">{List}<div id="{$_id}" class="ui segment" style="height: 60px"><i class="{iso}ae{/} flag"></i> {name}andorra{/}</div>{/}{$Content}</div>';

$listerContainer = \Phlex\Ui\View::addTo($scrollContainer, ['template' => new HtmlTemplate($listerTemplate)]);

$lister = \Phlex\Ui\Lister::addTo($listerContainer, [], ['List']);
$lister->onHook(\Phlex\Ui\Lister::HOOK_BEFORE_ROW, function (\Phlex\Ui\Lister $lister, Country $row) {
    $row->iso = mb_strtolower($row->iso);
});
$lister->setModel(new Country($webpage->db));

// add dynamic scrolling.
$lister->addJsPaginator(20, ['stateContext' => '.atk-scroller'], $scrollContainer);
