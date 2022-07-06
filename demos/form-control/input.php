<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\Form;
use Phlex\Ui\Label;

/**
 * Testing fields.
 */
/** @var \Phlex\Ui\Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

\Phlex\Ui\Header::addTo($webpage, ['Types', 'size' => 2]);

Form\Control\Line::addTo($webpage)->setDefaults(['placeholder' => 'Search']);
Form\Control\Line::addTo($webpage, ['placeholder' => 'Search', 'loading' => true]);
Form\Control\Line::addTo($webpage, ['placeholder' => 'Search', 'loading' => 'left']);
Form\Control\Line::addTo($webpage, ['placeholder' => 'Search', 'icon' => 'search', 'disabled' => true]);
Form\Control\Line::addTo($webpage, ['placeholder' => 'Search', 'error' => true]);

\Phlex\Ui\Header::addTo($webpage, ['Icon Variations', 'size' => 2]);

Form\Control\Line::addTo($webpage, ['placeholder' => 'Search users', 'left' => true, 'icon' => 'users']);
Form\Control\Line::addTo($webpage, ['placeholder' => 'Search users', 'icon' => 'circular search link']);
Form\Control\Line::addTo($webpage, ['placeholder' => 'Search users', 'icon' => 'inverted circular search link']);

\Phlex\Ui\Header::addTo($webpage, ['Labels', 'size' => 2]);

Form\Control\Line::addTo($webpage, ['placeholder' => 'Search users', 'label' => 'http://']);

// dropdown example
$dropdown = new \Phlex\Ui\Dropdown('.com');
$dropdown->setSource(['.com', '.net', '.org']);
Form\Control\Line::addTo($webpage, [
    'placeholder' => 'Find Domain',
    'labelRight' => $dropdown,
]);

Form\Control\Line::addTo($webpage, ['placeholder' => 'Weight', 'labelRight' => new Label(['kg', 'basic'])]);
Form\Control\Line::addTo($webpage, ['label' => '$', 'labelRight' => new Label(['.00', 'basic'])]);

Form\Control\Line::addTo($webpage, [
    'iconLeft' => 'tags',
    'labelRight' => new Label(['Add Tag', 'tag']),
]);

// left/right corner is not supported, but here is work-around:
$label = new Label();
$label->addClass('left corner');
\Phlex\Ui\Icon::addTo($label, ['asterisk']);

Form\Control\Line::addTo($webpage, [
    'label' => $label,
])->addClass('left corner');

$label = new Label();
$label->addClass('corner');
\Phlex\Ui\Icon::addTo($label, ['asterisk']);

Form\Control\Line::addTo($webpage, [
    'label' => $label,
])->addClass('corner');

\Phlex\Ui\Header::addTo($webpage, ['Actions', 'size' => 2]);

Form\Control\Line::addTo($webpage, ['action' => 'Search']);

Form\Control\Line::addTo($webpage, ['actionLeft' => new \Phlex\Ui\Button([
    'Checkout', 'icon' => 'cart', 'teal',
])]);

Form\Control\Line::addTo($webpage, ['iconLeft' => 'search',  'action' => 'Search']);

$dropdownButton = new \Phlex\Ui\DropdownButton(['This Page', 'basic']);
$dropdownButton->setSource(['This Organisation', 'Entire Site']);
Form\Control\Line::addTo($webpage, ['iconLeft' => 'search',  'action' => $dropdownButton]);

// double actions are not supported but you can add them yourself
$dropdown = new \Phlex\Ui\Dropdown(['Articles', 'compact selection']);
$dropdown->setSource(['All', 'Services', 'Products']);
\Phlex\Ui\Button::addTo(Form\Control\Line::addTo($webpage, ['iconLeft' => 'search',  'action' => $dropdown]), ['Search'], ['AfterAfterInput']);

Form\Control\Line::addTo($webpage, ['action' => new \Phlex\Ui\Button([
    'Copy', 'iconRight' => 'copy', 'teal',
])]);

Form\Control\Line::addTo($webpage, ['action' => new \Phlex\Ui\Button([
    'icon' => 'search',
])]);

\Phlex\Ui\Header::addTo($webpage, ['Modifiers', 'size' => 2]);

Form\Control\Line::addTo($webpage, ['icon' => 'search', 'transparent' => true, 'placeholder' => 'transparent']);
Form\Control\Line::addTo($webpage, ['icon' => 'search', 'fluid' => true, 'placeholder' => 'fluid']);

Form\Control\Line::addTo($webpage, ['icon' => 'search', 'mini' => true, 'placeholder' => 'mini']);

\Phlex\Ui\Header::addTo($webpage, ['Custom HTML attributes for <input> tag', 'size' => 2]);
$l = Form\Control\Line::addTo($webpage, ['placeholder' => 'maxlength attribute set to 10']);
$l->setInputAttr('maxlength', '10');
$l = Form\Control\Line::addTo($webpage, ['fluid' => true, 'placeholder' => 'overwrite existing attribute (type="number")']);
$l->setInputAttr(['type' => 'number']);
