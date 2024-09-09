<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\View;
use Phlex\Ui\Webpage;

/** @var Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

// Next line produces exception, which Phlex UI will catch and display nicely.
View::addTo($webpage, ['foo' => 'bar']);
