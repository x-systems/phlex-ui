<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\Button;
use Phlex\Ui\Header;
use Phlex\Ui\Text;
use Phlex\Ui\View;
use Phlex\Ui\Webpage;

/** @var Webpage $webpage */
require_once __DIR__ . '/init-app.php';

Header::addTo($webpage)->set('Welcome to Phlex UI Demo!!');

$t = Text::addTo(View::addTo($webpage, [false, 'green', 'ui' => 'segment']));
$t->addParagraph('Take a quick stroll through some of the amazing features of Phlex UI.');

Button::addTo($webpage, ['Begin the demo..', 'huge primary fluid', 'iconRight' => 'right arrow'])
    ->link('tutorial/intro.php');

Header::addTo($webpage)->set('What is new in Phlex UI 3.0');

$t = Text::addTo(View::addTo($webpage, [false, 'green', 'ui' => 'segment']));
$t->addParagraph('In this version of Phlex UI we introduce "User Actions"!');

Button::addTo($webpage, ['Learn about User Actions', 'huge basic primary fluid', 'iconRight' => 'right arrow'])
    ->link('tutorial/actions.php');
