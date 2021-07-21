<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\Form;

/** @var \Phlex\Ui\Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

\Phlex\Ui\Button::addTo($webpage, ['Nested accordions', 'small right floated basic blue', 'iconRight' => 'right arrow'])
    ->link(['accordion-nested']);
\Phlex\Ui\View::addTo($webpage, ['ui' => 'clearing divider']);

\Phlex\Ui\Header::addTo($webpage, ['Accordion\'s section can be control programmatically.']);

// toggle menu
$bar = \Phlex\Ui\View::addTo($webpage, ['ui' => 'buttons']);
$b1 = \Phlex\Ui\Button::addTo($bar, ['Toggle Section #1']);
$b2 = \Phlex\Ui\Button::addTo($bar, ['Toggle Section #2']);
$b3 = \Phlex\Ui\Button::addTo($bar, ['Toggle Section #3']);

\Phlex\Ui\Header::addTo($webpage, ['Accordion Sections']);

$accordion = \Phlex\Ui\Accordion::addTo($webpage, ['type' => ['styled', 'fluid']/*, 'settings'=>['exclusive'=>false]*/]);

// static section
$i1 = $accordion->addSection('Static Text');
\Phlex\Ui\Message::addTo($i1, ['This content is added on page loaded', 'ui' => 'tiny message']);
\Phlex\Ui\LoremIpsum::addTo($i1, ['size' => 1]);

// dynamic section - simple view
$i2 = $accordion->addSection('Dynamic Text', function ($v) {
    \Phlex\Ui\Message::addTo($v, ['Every time you open this accordion item, you will see a different text', 'ui' => 'tiny message']);
    \Phlex\Ui\LoremIpsum::addTo($v, ['size' => 2]);
});

// dynamic section - form view
$i3 = $accordion->addSection('Dynamic Form', function ($v) {
    \Phlex\Ui\Message::addTo($v, ['Loading a form dynamically.', 'ui' => 'tiny message']);
    $form = Form::addTo($v);
    $form->addControl('Email');
    $form->onSubmit(function (Form $form) {
        return $form->success('Subscribed ' . $form->model->get('Email') . ' to newsletter.');
    });
});

// Activate on page load.
$accordion->activate($i2);

$b1->on('click', $accordion->jsToggle($i1));
$b2->on('click', $accordion->jsToggle($i2));
$b3->on('click', $accordion->jsToggle($i3));
