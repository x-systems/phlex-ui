<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

/** @var \Phlex\Ui\Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

// Paginator which tracks its own position
\Phlex\Ui\Header::addTo($webpage, ['Paginator tracks its own position']);
\Phlex\Ui\Paginator::addTo($webpage, ['total' => 40, 'urlTrigger' => 'page']);

// Dynamically reloading paginator
\Phlex\Ui\Header::addTo($webpage, ['Dynamic reloading']);
$seg = \Phlex\Ui\View::addTo($webpage, ['ui' => 'blue segment']);
$label = \Phlex\Ui\Label::addTo($seg);
$bb = \Phlex\Ui\Paginator::addTo($seg, ['total' => 50, 'range' => 2, 'reload' => $seg]);
$label->addClass('blue ribbon');
$label->set('Current page: ' . $bb->page);

// Multiple dependent Paginators
\Phlex\Ui\Header::addTo($webpage, ['Local Sticky Usage']);
$seg = \Phlex\Ui\View::addTo($webpage, ['ui' => 'blue segment']);

$month = $seg->stickyGet('month') ?: 1;
$day = $seg->stickyGet('day') ?: 1;

// we intentionally left 31 days here and do not calculate number of days in particular month to keep example simple
$monthPaginator = \Phlex\Ui\Paginator::addTo($seg, ['total' => 12, 'range' => 3, 'urlTrigger' => 'month']);
\Phlex\Ui\View::addTo($seg, ['ui' => 'hidden divider']);
$dayPaginator = \Phlex\Ui\Paginator::addTo($seg, ['total' => 31, 'range' => 3, 'urlTrigger' => 'day']);
\Phlex\Ui\View::addTo($seg, ['ui' => 'hidden divider']);

$label = \Phlex\Ui\Label::addTo($seg);
$label->addClass('orange');
$label->set('Month: ' . $month . ' and Day: ' . $day);
