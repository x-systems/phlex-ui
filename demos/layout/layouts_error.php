<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

/** @var \Phlex\Ui\Webpage $app */
require_once __DIR__ . '/../init-app.php';

// Next line produces exception, which Agile UI will catch and display nicely.
\Phlex\Ui\View::addTo($app, ['foo' => 'bar']);
