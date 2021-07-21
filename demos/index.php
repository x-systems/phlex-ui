<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

/** @var \Phlex\Ui\Webpage $webpage */
require_once __DIR__ . '/init-app.php';

\Phlex\Ui\Header::addTo($webpage)->set('Welcome to Agile Toolkit Demo!!');

$t = \Phlex\Ui\Text::addTo(\Phlex\Ui\View::addTo($webpage, [false, 'green', 'ui' => 'segment']));
$t->addParagraph('Take a quick stroll through some of the amazing features of Agile Toolkit.');

\Phlex\Ui\Button::addTo($webpage, ['Begin the demo..', 'huge primary fluid', 'iconRight' => 'right arrow'])
    ->link('tutorial/intro.php');

\Phlex\Ui\Header::addTo($webpage)->set('What is new in Agile Toolkit 2.0');

$t = \Phlex\Ui\Text::addTo(\Phlex\Ui\View::addTo($webpage, [false, 'green', 'ui' => 'segment']));
$t->addParagraph('In this version of Agile Toolkit we introduce "User Actions"!');

\Phlex\Ui\Button::addTo($webpage, ['Learn about User Actions', 'huge basic primary fluid', 'iconRight' => 'right arrow'])
    ->link('tutorial/actions.php');
