<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\JsSse;

/** @var \Phlex\Ui\Webpage $app */
require_once __DIR__ . '/../init-app.php';

$sse = JsSse::addTo($app);
$sse->setUrlTrigger('console_test');

$console = \Phlex\Ui\Console::addTo($app, ['sse' => $sse]);

$console->set(function ($console) {
    $console->output('Executing test process...');
    $console->output('Now trying something dangerous..');
    echo 'direct output is captured';

    throw new \Phlex\Core\Exception('BOOM - exceptions are caught');
});
