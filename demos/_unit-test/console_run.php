<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Core\DebugTrait;
use Phlex\Ui\Console;
use Phlex\Ui\JsSse;
use Phlex\Ui\View;
use Phlex\Ui\Webpage;

/** @var Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

/** @var View $testRunClass */
$testRunClass = get_class(new class() extends View {
    use DebugTrait;

    public function test()
    {
        $this->log('info', 'Console will automatically pick up output from all DebugTrait objects');
        $this->debug('debug');
        $this->emergency('emergency');
        $this->alert('alert');
        $this->critical('critical');
        $this->error('error');
        $this->warning('warning');
        $this->notice('notice');
        $this->info('info');

        return 123;
    }
});

$sse = JsSse::addTo($webpage);
$sse->setUrlTrigger('console_test');

$console = Console::addTo($webpage, ['sse' => $sse]);
$console->runMethod($testRunClass::addTo($webpage), 'test');
