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

Button::addTo($webpage, ['Dynamic scroll in Table', 'small left floated basic blue', 'icon' => 'left arrow'])
    ->link(['scroll-table']);
Button::addTo($webpage, ['Dynamic scroll in Grid', 'small right floated basic blue', 'iconRight' => 'right arrow'])
    ->link(['scroll-grid']);
View::addTo($webpage, ['ui' => 'ui clearing divider']);

Header::addTo($webpage, ['Dynamic scroll in Container']);

$view = View::addTo($webpage)->addClass('ui basic segment phlex-scroller');

$scrollContainer = View::addTo($view)->addClass('ui segment')->addStyle(['max-height' => '400px', 'overflow-y' => 'scroll']);

$listerTemplate = '<div id="{$_id}">{List}<div id="{$_id}" class="ui segment" style="height: 60px"><i class="{iso}ae{/} flag"></i> {name}andorra{/}</div>{/}{$Content}</div>';

$listerContainer = View::addTo($scrollContainer, ['template' => new HtmlTemplate($listerTemplate)]);

$lister = Lister::addTo($listerContainer, [], ['List']);
$lister->onHook(Lister::HOOK_BEFORE_ROW, static function (Lister $lister, Country $row) {
    $row->iso = mb_strtolower($row->iso);
});
$lister->setModel(new Country($webpage->db));

// add dynamic scrolling.
$lister->addJsPaginator(20, ['stateContext' => '.phlex-scroller'], $scrollContainer);
