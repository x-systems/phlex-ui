<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Core\Exception;
use Phlex\Ui\Console;
use Phlex\Ui\JsSse;
use Phlex\Ui\Webpage;

/** @var Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

$sse = JsSse::addTo($webpage);
$sse->setUrlTrigger('console_test');

$console = Console::addTo($webpage, ['sse' => $sse]);

$console->set(function ($console) {
    $console->output('Executing test process...');
    $console->output('Now trying something dangerous..');
    echo 'direct output is captured';

    throw new Exception('BOOM - exceptions are caught');
});
