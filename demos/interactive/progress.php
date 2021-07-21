<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

/**
 * Demonstrates how to use tabs.
 */
/** @var \Phlex\Ui\Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

$p = \Phlex\Ui\ProgressBar::addTo($webpage, [20]);

$p = \Phlex\Ui\ProgressBar::addTo($webpage, [60, 'indicating progress', 'indicating']);
\Phlex\Ui\Button::addTo($webpage, ['increment'])->on('click', $p->jsIncrement());
\Phlex\Ui\Button::addTo($webpage, ['set'])->on('click', $p->jsValue(20));
