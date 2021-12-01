<?php

declare(strict_types=1);

namespace Phlex\Ui\Tests;

class WebpageMock extends \Phlex\Ui\Webpage
{
    public $terminated = false;

    public function terminate($output = '', array $headers = []): void
    {
        $this->terminate = true;
    }

    /**
     * Overrided to allow multiple App::run() calls, prevent sending headers when headers are already sent.
     */
    protected function outputResponse(string $data, array $headers): void
    {
        echo $data;
    }
}

class CallbackTest extends \Phlex\Core\PHPUnit\TestCase
{
    /** @var string */
    private $htmlDoctypeRegex = '~^<!DOCTYPE~';

    /** @var \Phlex\Ui\Webpage */
    public $app;

    protected function setUp(): void
    {
        $this->app = new WebpageMock(['always_run' => false, 'catch_exceptions' => false]);
        $this->app->initBody([\Phlex\Ui\Layout\Centered::class]);

        // reset var, between tests
        $_GET = [];
        $_POST = [];
    }

    public function testCallback(): void
    {
        $var = null;

        $cb = \Phlex\Ui\Callback::addTo($this->app);

        // simulate triggering
        $_GET[$cb->elementName] = '1';

        $cb->set(function ($x) use (&$var) {
            $var = $x;
        }, [34]);

        $this->assertSame(34, $var);
    }

    public function testCallbackNotFiring(): void
    {
        $var = null;

        $cb = \Phlex\Ui\Callback::addTo($this->app);

        // don't simulate triggering
        $cb->set(function ($x) use (&$var) {
            $var = $x;
        }, [34]);

        $this->assertNull($var);
    }

    public function testCallbackLater(): void
    {
        $var = null;

        $cb = \Phlex\Ui\CallbackLater::addTo($this->app);

        // simulate triggering
        $_GET[$cb->elementName] = '1';

        $cb->set(function ($x) use (&$var) {
            $var = $x;
        }, [34]);

        $this->assertNull($var);

        $this->expectOutputRegex($this->htmlDoctypeRegex);
        $this->app->run();

        $this->assertSame(34, $var);
    }

    public function testCallbackLaterNested(): void
    {
        $var = null;

        $cb = \Phlex\Ui\CallbackLater::addTo($this->app);

        // simulate triggering
        $_GET[$cb->elementName] = '1';
        $_GET[$cb->elementName . '_2'] = '1';

        $webpage = $this->app;
        $cb->set(function ($x) use (&$var, $webpage, &$cbname) {
            $cb2 = \Phlex\Ui\CallbackLater::addTo($webpage);
            $cbname = $cb2->elementName;
            $cb2->set(function ($y) use (&$var) {
                $var = $y;
            }, [$x]);
        }, [34]);

        $this->assertNull($var);

        $this->expectOutputRegex($this->htmlDoctypeRegex);
        $this->app->run();

        $this->assertSame(34, $var);
    }

    public function testCallbackLaterNotFiring(): void
    {
        $var = null;

        $cb = \Phlex\Ui\CallbackLater::addTo($this->app);

        // don't simulate triggering
        $cb->set(function ($x) use (&$var) {
            $var = $x;
        }, [34]);

        $this->assertNull($var);

        $this->expectOutputRegex($this->htmlDoctypeRegex);
        $this->app->run();

        $this->assertNull($var);
    }

    public function testVirtualPage(): void
    {
        $var = null;

        $vp = \Phlex\Ui\VirtualPage::addTo($this->app);

        // simulate triggering
        $_GET[$vp->elementName] = '1';

        $vp->set(function ($p) use (&$var) {
            $var = 25;
        });

        $this->expectOutputRegex('/^..DOCTYPE/');
        $this->app->run();
        $this->assertSame(25, $var);
    }

    public function testVirtualPageCustomTrigger(): void
    {
        $var = null;

        $vp = \Phlex\Ui\VirtualPage::addTo($this->app, ['urlTrigger' => 'bah']);

        // simulate triggering
        $_GET['bah'] = '1';

        $vp->set(function ($p) use (&$var) {
            $var = 25;
        });

        $this->expectOutputRegex('/^..DOCTYPE/');
        $this->app->run();
        $this->assertSame(25, $var);
    }

    public $var;

    public function callPull230()
    {
        $this->var = 26;
    }

    public function testPull230(): void
    {
        $var = null;

        $vp = \Phlex\Ui\VirtualPage::addTo($this->app);

        // simulate triggering
        $_GET[$vp->elementName] = '1';

        $vp->set(\Closure::fromCallable([$this, 'callPull230']));

        $this->expectOutputRegex('/^..DOCTYPE/');
        $this->app->run();
        $this->assertSame(26, $this->var);
    }
}
