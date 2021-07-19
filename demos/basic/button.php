<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\Button;
use Phlex\Ui\HtmlTemplate;
use Phlex\Ui\Icon;
use Phlex\Ui\Label;

/** @var \Phlex\Ui\App $app */
require_once __DIR__ . '/../init-app.php';

// Demonstrates how to use buttons.

\Phlex\Ui\Header::addTo($app, ['Basic Button', 'size' => 2]);

// With Seed
Button::addTo($app, ['Click me'])->link(['index']);

// Without Seeding
$b1 = new Button('Click me (no seed)');
$app->addView($b1);
// must be added first
$b1->link(['index']);

\Phlex\Ui\Header::addTo($app, ['Properties', 'size' => 2]);
Button::addTo($app, ['Primary button', 'primary']);
Button::addTo($app, ['Load', 'labeled', 'icon' => 'pause']);
Button::addTo($app, ['Next', 'iconRight' => 'right arrow']);
Button::addTo($app, [null, 'circular', 'icon' => 'settings']);

\Phlex\Ui\Header::addTo($app, ['Big Button', 'size' => 2]);
Button::addTo($app, ['Click me', 'big primary', 'icon' => 'check']);

\Phlex\Ui\Header::addTo($app, ['Button Intent', 'size' => 2]);
Button::addTo($app, ['Yes', 'positive basic']);
Button::addTo($app, ['No', 'negative basic']);

\Phlex\Ui\Header::addTo($app, ['Combining Buttons', 'size' => 2]);

$bar = \Phlex\Ui\View::addTo($app, ['ui' => 'vertical buttons']);
Button::addTo($bar, ['Play', 'icon' => 'play']);
Button::addTo($bar, ['Pause', 'icon' => 'pause']);
Button::addTo($bar, ['Shuffle', 'icon' => 'shuffle']);

\Phlex\Ui\Header::addTo($app, ['Icon Bar', 'size' => 2]);
$bar = \Phlex\Ui\View::addTo($app, ['ui' => 'big blue buttons']);
Button::addTo($bar, ['icon' => 'file']);
Button::addTo($bar, ['icon' => 'yellow save']);
Button::addTo($bar, ['icon' => 'upload', 'disabled' => true]);

\Phlex\Ui\Header::addTo($app, ['Forks Button Component', 'size' => 2]);

// Creating your own button component example

/** @var Button $forkButtonClass */
$forkButtonClass = get_class(new class(0) extends Button { // need 0 argument here for constructor
    public function __construct($n)
    {
        Icon::addTo(Button::addTo($this, ['Forks', 'blue']), ['fork']);
        Label::addTo($this, [number_format($n), 'basic blue left pointing']);
        parent::__construct(null, 'labeled');
    }
});

$forkButton = new $forkButtonClass(1234 + random_int(1, 100));
$app->addView($forkButton);

\Phlex\Ui\Header::addTo($app, ['Custom Template', 'size' => 2]);

$view = \Phlex\Ui\View::addTo($app, ['template' => new HtmlTemplate('Hello, {$tag1}, my name is {$tag2}')]);

Button::addTo($view, ['World'], ['tag1']);
Button::addTo($view, ['Agile UI', 'blue'], ['tag2']);

\Phlex\Ui\Header::addTo($app, ['Attaching', 'size' => 2]);

Button::addTo($app, ['Previous', 'top attached']);
\Phlex\Ui\Table::addTo($app, ['attached', 'header' => false])
    ->setSource(['One', 'Two', 'Three', 'Four']);
Button::addTo($app, ['Next', 'bottom attached']);
