<?php

declare(strict_types=1);

namespace Phlex\Ui\Tests;

/**
 * Same as DemosHttpTest, only Webpage::call_exit is set to false.
 *
 * @group demos_http
 */
class DemosHttpNoExitTest extends DemosHttpTest
{
    /** @var bool set the app->call_exit in demo */
    protected $app_call_exit = false;
}
