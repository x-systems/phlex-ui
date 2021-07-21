<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

/** @var \Phlex\Ui\Webpage $app */
require_once __DIR__ . '/../init-app.php';

\Phlex\Ui\Button::addTo($app, ['Loader Examples - Page 2', 'small right floated basic blue', 'iconRight' => 'right arrow'])
    ->link(['loader2']);

\Phlex\Ui\View::addTo($app, ['ui' => 'clearing divider']);

// ViewTester will perform callback to self.
ViewTester::addTo($app);

// Example 1 - Basic usage of a Loader.
\Phlex\Ui\Loader::addTo($app)->set(function ($p) {
    // set your time expensive function here.
    sleep(1);
    \Phlex\Ui\Header::addTo($p, ['Loader #1']);
    \Phlex\Ui\LoremIpsum::addTo($p, ['size' => 1]);

    // Any dynamic views can perform call-backs just fine
    ViewTester::addTo($p);

    // Loader may be inside another loader, works fine.
    $loader = \Phlex\Ui\Loader::addTo($p);

    // use loadEvent to prevent manual loading or even specify custom trigger event
    $loader->loadEvent = false;
    $loader->set(function ($p) {
        // You may pass arguments to the loader, in this case it's "color"
        sleep(1);
        \Phlex\Ui\Header::addTo($p, ['Loader #1b - ' . $_GET['color']]);
        \Phlex\Ui\LoremIpsum::addTo(\Phlex\Ui\View::addTo($p, ['ui' => $_GET['color'] . ' segment']), ['size' => 1]);

        // don't forget to make your own argument sticky so that Components can communicate with themselves:
        $p->getApp()->stickyGet('color');
        ViewTester::addTo($p);

        // This loader takes 2s to load because it needs to go through 2 sleep statements.
    });

    // button may contain load event.
    \Phlex\Ui\Button::addTo($p, ['Load Segment Manually (2s)', 'red'])->js('click', $loader->jsLoad(['color' => 'red']));
    \Phlex\Ui\Button::addTo($p, ['Load Segment Manually (2s)', 'blue'])->js('click', $loader->jsLoad(['color' => 'blue']));
});

// Example 2 - Loader with custom body.
\Phlex\Ui\Loader::addTo($app, [
    'ui' => '',   // this will prevent "loading spinner" from showing
    'shim' => [   // shim is displayed while content is leaded
        \Phlex\Ui\Message::class,
        'Generating LoremIpsum, please wait...',
        'red',
    ],
])->set(function ($p) {
    usleep(500 * 1000);
    \Phlex\Ui\LoremIpsum::addTo($p, ['size' => 2]);
});
