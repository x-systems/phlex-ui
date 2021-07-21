<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\Form;

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
$dd = new \Phlex\Ui\Dropdown('.com');
$dd->setSource(['.com', '.net', '.org']);
Form\Control\Line::addTo($webpage, [
    'placeholder' => 'Find Domain',
    'labelRight' => $dd,
]);

Form\Control\Line::addTo($webpage, ['placeholder' => 'Weight', 'labelRight' => new \Phlex\Ui\Label(['kg', 'basic'])]);
Form\Control\Line::addTo($webpage, ['label' => '$', 'labelRight' => new \Phlex\Ui\Label(['.00', 'basic'])]);

Form\Control\Line::addTo($webpage, [
    'iconLeft' => 'tags',
    'labelRight' => new \Phlex\Ui\Label(['Add Tag', 'tag']),
]);

// left/right corner is not supported, but here is work-around:
$label = new \Phlex\Ui\Label();
$label->addClass('left corner');
\Phlex\Ui\Icon::addTo($label, ['asterisk']);

Form\Control\Line::addTo($webpage, [
    'label' => $label,
])->addClass('left corner');

$label = new \Phlex\Ui\Label();
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

$dd = new \Phlex\Ui\DropdownButton(['This Page', 'basic']);
$dd->setSource(['This Organisation', 'Entire Site']);
Form\Control\Line::addTo($webpage, ['iconLeft' => 'search',  'action' => $dd]);

// double actions are not supported but you can add them yourself
$dd = new \Phlex\Ui\Dropdown(['Articles', 'compact selection']);
$dd->setSource(['All', 'Services', 'Products']);
\Phlex\Ui\Button::addTo(Form\Control\Line::addTo($webpage, ['iconLeft' => 'search',  'action' => $dd]), ['Search'], ['AfterAfterInput']);

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
