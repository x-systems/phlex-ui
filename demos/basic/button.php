<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\Button;
use Phlex\Ui\HtmlTemplate;
use Phlex\Ui\Icon;
use Phlex\Ui\Label;

/** @var \Phlex\Ui\Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

// Demonstrates how to use buttons.

\Phlex\Ui\Header::addTo($webpage, ['Basic Button', 'size' => 2]);

// With Seed
Button::addTo($webpage, ['Click me'])->link(['index']);

// Without Seeding
$b1 = new Button('Click me (no seed)');
$webpage->addView($b1);
// must be added first
$b1->link(['index']);

\Phlex\Ui\Header::addTo($webpage, ['Properties', 'size' => 2]);
Button::addTo($webpage, ['Primary button', 'primary']);
Button::addTo($webpage, ['Load', 'labeled', 'icon' => 'pause']);
Button::addTo($webpage, ['Next', 'iconRight' => 'right arrow']);
Button::addTo($webpage, [null, 'circular', 'icon' => 'settings']);

\Phlex\Ui\Header::addTo($webpage, ['Big Button', 'size' => 2]);
Button::addTo($webpage, ['Click me', 'big primary', 'icon' => 'check']);

\Phlex\Ui\Header::addTo($webpage, ['Button Intent', 'size' => 2]);
Button::addTo($webpage, ['Yes', 'positive basic']);
Button::addTo($webpage, ['No', 'negative basic']);

\Phlex\Ui\Header::addTo($webpage, ['Combining Buttons', 'size' => 2]);

$bar = \Phlex\Ui\View::addTo($webpage, ['ui' => 'vertical buttons']);
Button::addTo($bar, ['Play', 'icon' => 'play']);
Button::addTo($bar, ['Pause', 'icon' => 'pause']);
Button::addTo($bar, ['Shuffle', 'icon' => 'shuffle']);

\Phlex\Ui\Header::addTo($webpage, ['Icon Bar', 'size' => 2]);
$bar = \Phlex\Ui\View::addTo($webpage, ['ui' => 'big blue buttons']);
Button::addTo($bar, ['icon' => 'file']);
Button::addTo($bar, ['icon' => 'yellow save']);
Button::addTo($bar, ['icon' => 'upload', 'disabled' => true]);

\Phlex\Ui\Header::addTo($webpage, ['Forks Button Component', 'size' => 2]);

// Creating your own button component example

/** @var Button $forkButtonClass */
$forkButtonClass = get_class(new class(0) extends Button {
    // need 0 argument here for constructor
    public function __construct($n)
    {
        Icon::addTo(Button::addTo($this, ['Forks', 'blue']), ['fork']);
        Label::addTo($this, [number_format($n), 'basic blue left pointing']);
        parent::__construct(null, 'labeled');
    }
});

$forkButton = new $forkButtonClass(1234 + random_int(1, 100));
$webpage->addView($forkButton);

\Phlex\Ui\Header::addTo($webpage, ['Custom Template', 'size' => 2]);

$view = \Phlex\Ui\View::addTo($webpage, ['template' => new HtmlTemplate('Hello, {$tag1}, my name is {$tag2}')]);

Button::addTo($view, ['World'], ['tag1']);
Button::addTo($view, ['Agile UI', 'blue'], ['tag2']);

\Phlex\Ui\Header::addTo($webpage, ['Attaching', 'size' => 2]);

Button::addTo($webpage, ['Previous', 'top attached']);
\Phlex\Ui\Table::addTo($webpage, ['attached', 'header' => false])
    ->setSource(['One', 'Two', 'Three', 'Four']);
Button::addTo($webpage, ['Next', 'bottom attached']);
