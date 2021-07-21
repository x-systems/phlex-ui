<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\JsSse;

/** @var \Phlex\Ui\Webpage $app */
require_once __DIR__ . '/../init-app.php';

$sse = JsSse::addTo($app);
$sse->setUrlTrigger('console_test');

$console = \Phlex\Ui\Console::addTo($app, ['sse' => $sse]);
$console->exec('/bin/pwd');
