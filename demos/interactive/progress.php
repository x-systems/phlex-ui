<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\Button;
use Phlex\Ui\ProgressBar;
use Phlex\Ui\Webpage;

/**
 * Demonstrates how to use tabs.
 */
/** @var Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

$p = ProgressBar::addTo($webpage, [20]);

$p = ProgressBar::addTo($webpage, [60, 'indicating progress', 'indicating']);
Button::addTo($webpage, ['increment'])->on('click', $p->jsIncrement());
Button::addTo($webpage, ['set'])->on('click', $p->jsValue(20));
