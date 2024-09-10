<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\Accordion;
use Phlex\Ui\Button;
use Phlex\Ui\Form;
use Phlex\Ui\Header;
use Phlex\Ui\LoremIpsum;
use Phlex\Ui\Message;
use Phlex\Ui\View;
use Phlex\Ui\Webpage;

/** @var Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

Button::addTo($webpage, ['Nested accordions', 'small right floated basic blue', 'iconRight' => 'right arrow'])
    ->link(['accordion-nested']);
View::addTo($webpage, ['ui' => 'clearing divider']);

Header::addTo($webpage, ['Accordion\'s section can be control programmatically.']);

// toggle menu
$bar = View::addTo($webpage, ['ui' => 'buttons']);
$b1 = Button::addTo($bar, ['Toggle Section #1']);
$b2 = Button::addTo($bar, ['Toggle Section #2']);
$b3 = Button::addTo($bar, ['Toggle Section #3']);

Header::addTo($webpage, ['Accordion Sections']);

$accordion = Accordion::addTo($webpage, ['type' => ['styled', 'fluid']/* , 'settings'=>['exclusive'=>false] */]);

// static section
$i1 = $accordion->addSection('Static Text');
Message::addTo($i1, ['This content is added on page loaded', 'ui' => 'tiny message']);
LoremIpsum::addTo($i1, ['size' => 1]);

// dynamic section - simple view
$i2 = $accordion->addSection('Dynamic Text', function ($v) {
    Message::addTo($v, ['Every time you open this accordion item, you will see a different text', 'ui' => 'tiny message']);
    LoremIpsum::addTo($v, ['size' => 2]);
});

// dynamic section - form view
$i3 = $accordion->addSection('Dynamic Form', function ($v) {
    Message::addTo($v, ['Loading a form dynamically.', 'ui' => 'tiny message']);
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
