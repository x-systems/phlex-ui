<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\Form;

/**
 * Testing fields.
 */
/** @var \Phlex\Ui\App $app */
require_once __DIR__ . '/../init-app.php';

\Phlex\Ui\Header::addTo($app, ['Types', 'size' => 2]);

Form\Control\Line::addTo($app)->setDefaults(['placeholder' => 'Search']);
Form\Control\Line::addTo($app, ['placeholder' => 'Search', 'loading' => true]);
Form\Control\Line::addTo($app, ['placeholder' => 'Search', 'loading' => 'left']);
Form\Control\Line::addTo($app, ['placeholder' => 'Search', 'icon' => 'search', 'disabled' => true]);
Form\Control\Line::addTo($app, ['placeholder' => 'Search', 'error' => true]);

\Phlex\Ui\Header::addTo($app, ['Icon Variations', 'size' => 2]);

Form\Control\Line::addTo($app, ['placeholder' => 'Search users', 'left' => true, 'icon' => 'users']);
Form\Control\Line::addTo($app, ['placeholder' => 'Search users', 'icon' => 'circular search link']);
Form\Control\Line::addTo($app, ['placeholder' => 'Search users', 'icon' => 'inverted circular search link']);

\Phlex\Ui\Header::addTo($app, ['Labels', 'size' => 2]);

Form\Control\Line::addTo($app, ['placeholder' => 'Search users', 'label' => 'http://']);

// dropdown example
$dd = new \Phlex\Ui\Dropdown('.com');
$dd->setSource(['.com', '.net', '.org']);
Form\Control\Line::addTo($app, [
    'placeholder' => 'Find Domain',
    'labelRight' => $dd,
]);

Form\Control\Line::addTo($app, ['placeholder' => 'Weight', 'labelRight' => new \Phlex\Ui\Label(['kg', 'basic'])]);
Form\Control\Line::addTo($app, ['label' => '$', 'labelRight' => new \Phlex\Ui\Label(['.00', 'basic'])]);

Form\Control\Line::addTo($app, [
    'iconLeft' => 'tags',
    'labelRight' => new \Phlex\Ui\Label(['Add Tag', 'tag']),
]);

// left/right corner is not supported, but here is work-around:
$label = new \Phlex\Ui\Label();
$label->addClass('left corner');
\Phlex\Ui\Icon::addTo($label, ['asterisk']);

Form\Control\Line::addTo($app, [
    'label' => $label,
])->addClass('left corner');

$label = new \Phlex\Ui\Label();
$label->addClass('corner');
\Phlex\Ui\Icon::addTo($label, ['asterisk']);

Form\Control\Line::addTo($app, [
    'label' => $label,
])->addClass('corner');

\Phlex\Ui\Header::addTo($app, ['Actions', 'size' => 2]);

Form\Control\Line::addTo($app, ['action' => 'Search']);

Form\Control\Line::addTo($app, ['actionLeft' => new \Phlex\Ui\Button([
    'Checkout', 'icon' => 'cart', 'teal',
])]);

Form\Control\Line::addTo($app, ['iconLeft' => 'search',  'action' => 'Search']);

$dd = new \Phlex\Ui\DropdownButton(['This Page', 'basic']);
$dd->setSource(['This Organisation', 'Entire Site']);
Form\Control\Line::addTo($app, ['iconLeft' => 'search',  'action' => $dd]);

// double actions are not supported but you can add them yourself
$dd = new \Phlex\Ui\Dropdown(['Articles', 'compact selection']);
$dd->setSource(['All', 'Services', 'Products']);
\Phlex\Ui\Button::addTo(Form\Control\Line::addTo($app, ['iconLeft' => 'search',  'action' => $dd]), ['Search'], ['AfterAfterInput']);

Form\Control\Line::addTo($app, ['action' => new \Phlex\Ui\Button([
    'Copy', 'iconRight' => 'copy', 'teal',
])]);

Form\Control\Line::addTo($app, ['action' => new \Phlex\Ui\Button([
    'icon' => 'search',
])]);

\Phlex\Ui\Header::addTo($app, ['Modifiers', 'size' => 2]);

Form\Control\Line::addTo($app, ['icon' => 'search', 'transparent' => true, 'placeholder' => 'transparent']);
Form\Control\Line::addTo($app, ['icon' => 'search', 'fluid' => true, 'placeholder' => 'fluid']);

Form\Control\Line::addTo($app, ['icon' => 'search', 'mini' => true, 'placeholder' => 'mini']);

\Phlex\Ui\Header::addTo($app, ['Custom HTML attributes for <input> tag', 'size' => 2]);
$l = Form\Control\Line::addTo($app, ['placeholder' => 'maxlength attribute set to 10']);
$l->setInputAttr('maxlength', '10');
$l = Form\Control\Line::addTo($app, ['fluid' => true, 'placeholder' => 'overwrite existing attribute (type="number")']);
$l->setInputAttr(['type' => 'number']);
