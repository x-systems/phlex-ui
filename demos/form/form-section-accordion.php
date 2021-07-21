<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\Form;

/** @var \Phlex\Ui\Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

\Phlex\Ui\Button::addTo($webpage, ['Form Sections', 'small left floated basic blue', 'icon' => 'left arrow'])
    ->link(['form-section']);
\Phlex\Ui\View::addTo($webpage, ['ui' => 'ui clearing divider']);

$form = Form::addTo($webpage);

$sublayout = $form->layout->addSubLayout([\Phlex\Ui\Form\Layout\Section::class]);

\Phlex\Ui\Header::addTo($sublayout, ['Please fill all form sections!', 'size' => 4]);

$sublayout->addControl('company_name');

// Accordion
$accordionLayout = $form->layout->addSubLayout([Form\Layout\Section\Accordion::class, 'type' => ['styled', 'fluid'], 'settings' => ['exclusive' => false]]);

// Section #1
$contactSection = $accordionLayout->addSection('Contact');

$group = $contactSection->addGroup('Name');
$group->addControl('first_name', ['width' => 'eight'], ['required' => true]);
$group->addControl('last_name', ['width' => 'eight']);

$group = $contactSection->addGroup('Email');
$group->addControl('email', ['width' => 'sixteen'], ['caption' => 'yourEmail@domain.com']);

// Section #2
$addressSection = $accordionLayout->addSection('Address');

$group = $addressSection->addGroup('Street and City');
$group->addControl('address1', ['width' => 'eight'], ['required' => true]);
$group->addControl('city', ['width' => 'eight']);

$group = $addressSection->addGroup('State, Country and Postal Code');
$group->addControl('state', ['width' => 'six']);
$group->addControl('country', ['width' => 'six']);
$group->addControl('postal', ['width' => 'four']);

// Sub-Accordion
$sublayoutAccordion = $addressSection->addSubLayout([Form\Layout\Section\Accordion::class, 'type' => ['styled', 'fluid'], 'settings' => ['exclusive' => false]]);

// Sub-Section #1
$section1 = $sublayoutAccordion->addSection('Business address');
$section1->addControl('business_address');

// Sub-Section #2
$section2 = $sublayoutAccordion->addSection('Delivery address');
$section2->addControl('delivery_address', []);

// Terms field
$form->addControl('term', [Form\Control\Checkbox::class, 'caption' => 'Accept terms and conditions', null, 'slider']);

$accordionLayout->activate($contactSection);

$form->onSubmit(function (Form $form) {
    return $form->success('Yey!', 'You did well by filling out this form');
});
