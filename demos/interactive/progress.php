<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

/**
 * Demonstrates how to use tabs.
 */
/** @var \Phlex\Ui\Webpage $app */
require_once __DIR__ . '/../init-app.php';

$p = \Phlex\Ui\ProgressBar::addTo($app, [20]);

$p = \Phlex\Ui\ProgressBar::addTo($app, [60, 'indicating progress', 'indicating']);
\Phlex\Ui\Button::addTo($app, ['increment'])->on('click', $p->jsIncrement());
\Phlex\Ui\Button::addTo($app, ['set'])->on('click', $p->jsValue(20));
