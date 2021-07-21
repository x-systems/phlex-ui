<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\JsSse;

/** @var \Phlex\Ui\Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

$sse = JsSse::addTo($webpage);
$sse->setUrlTrigger('console_test');

$console = \Phlex\Ui\Console::addTo($webpage, ['sse' => $sse]);
$console->exec('/bin/pwd');
