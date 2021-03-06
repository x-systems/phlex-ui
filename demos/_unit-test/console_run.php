<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\JsSse;

/** @var \Phlex\Ui\App $app */
require_once __DIR__ . '/../init-app.php';

/** @var \Phlex\Ui\View $testRunClass */
$testRunClass = get_class(new class() extends \Phlex\Ui\View {
    use \Phlex\Core\DebugTrait;

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

$sse = JsSse::addTo($app);
$sse->setUrlTrigger('console_test');

$console = \Phlex\Ui\Console::addTo($app, ['sse' => $sse]);
$console->runMethod($testRunClass::addTo($app), 'test');
