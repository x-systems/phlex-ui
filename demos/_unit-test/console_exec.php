<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\Console;
use Phlex\Ui\JsSse;
use Phlex\Ui\Webpage;

/** @var Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

$sse = JsSse::addTo($webpage);
$sse->setUrlTrigger('console_test');

$console = Console::addTo($webpage, ['sse' => $sse]);
$console->exec('/bin/pwd');
