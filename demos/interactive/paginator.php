<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\Header;
use Phlex\Ui\Label;
use Phlex\Ui\Paginator;
use Phlex\Ui\View;
use Phlex\Ui\Webpage;

/** @var Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

// Paginator which tracks its own position
Header::addTo($webpage, ['Paginator tracks its own position']);
Paginator::addTo($webpage, ['total' => 40, 'urlTrigger' => 'page']);

// Dynamically reloading paginator
Header::addTo($webpage, ['Dynamic reloading']);
$seg = View::addTo($webpage, ['ui' => 'blue segment']);
$label = Label::addTo($seg);
$bb = Paginator::addTo($seg, ['total' => 50, 'range' => 2, 'reload' => $seg]);
$label->addClass('blue ribbon');
$label->set('Current page: ' . $bb->page);

// Multiple dependent Paginators
Header::addTo($webpage, ['Local Sticky Usage']);
$seg = View::addTo($webpage, ['ui' => 'blue segment']);

$month = $seg->stickyGet('month') ?: 1;
$day = $seg->stickyGet('day') ?: 1;

// we intentionally left 31 days here and do not calculate number of days in particular month to keep example simple
$monthPaginator = Paginator::addTo($seg, ['total' => 12, 'range' => 3, 'urlTrigger' => 'month']);
View::addTo($seg, ['ui' => 'hidden divider']);
$dayPaginator = Paginator::addTo($seg, ['total' => 31, 'range' => 3, 'urlTrigger' => 'day']);
View::addTo($seg, ['ui' => 'hidden divider']);

$label = Label::addTo($seg);
$label->addClass('orange');
$label->set('Month: ' . $month . ' and Day: ' . $day);
