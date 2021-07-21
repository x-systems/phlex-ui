<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

/** @var \Phlex\Ui\Webpage $app */
require_once __DIR__ . '/init-app.php';

\Phlex\Ui\Header::addTo($app)->set('Welcome to Agile Toolkit Demo!!');

$t = \Phlex\Ui\Text::addTo(\Phlex\Ui\View::addTo($app, [false, 'green', 'ui' => 'segment']));
$t->addParagraph('Take a quick stroll through some of the amazing features of Agile Toolkit.');

\Phlex\Ui\Button::addTo($app, ['Begin the demo..', 'huge primary fluid', 'iconRight' => 'right arrow'])
    ->link('tutorial/intro.php');

\Phlex\Ui\Header::addTo($app)->set('What is new in Agile Toolkit 2.0');

$t = \Phlex\Ui\Text::addTo(\Phlex\Ui\View::addTo($app, [false, 'green', 'ui' => 'segment']));
$t->addParagraph('In this version of Agile Toolkit we introduce "User Actions"!');

\Phlex\Ui\Button::addTo($app, ['Learn about User Actions', 'huge basic primary fluid', 'iconRight' => 'right arrow'])
    ->link('tutorial/actions.php');
