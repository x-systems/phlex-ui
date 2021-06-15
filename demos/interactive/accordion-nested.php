<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

/** @var \Phlex\Ui\App $app */
require_once __DIR__ . '/../init-app.php';

/*
\Phlex\Ui\Button::addTo($app, ['View Form input split in Accordion section', 'small right floated basic blue', 'iconRight' => 'right arrow'])
    ->link(['accordion-in-form']);
\Phlex\Ui\View::addTo($app, ['ui' => 'clearing divider']);
*/

\Phlex\Ui\Header::addTo($app, ['Nested accordions']);

$addAccordionFunc = function ($view, $maxDepth = 2, $level = 0) use (&$addAccordionFunc) {
    $accordion = \Phlex\Ui\Accordion::addTo($view, ['type' => ['styled', 'fluid']]);

    // static section
    $i1 = $accordion->addSection('Static Text');
    \Phlex\Ui\Message::addTo($i1, ['This content is added on page loaded', 'ui' => 'tiny message']);
    \Phlex\Ui\LoremIpsum::addTo($i1, ['size' => 1]);
    if ($level < $maxDepth) {
        $addAccordionFunc($i1, $maxDepth, $level + 1);
    }

    // dynamic section - simple view
    $i2 = $accordion->addSection('Dynamic Text', function ($v) use ($addAccordionFunc, $maxDepth, $level) {
        \Phlex\Ui\Message::addTo($v, ['Every time you open this accordion item, you will see a different text', 'ui' => 'tiny message']);
        \Phlex\Ui\LoremIpsum::addTo($v, ['size' => 2]);
        if ($level < $maxDepth) {
            $addAccordionFunc($v, $maxDepth, $level + 1);
        }
    });

    // dynamic section - form view
    $i3 = $accordion->addSection('Dynamic Form', function ($v) use ($addAccordionFunc, $maxDepth, $level) {
        \Phlex\Ui\Message::addTo($v, ['Loading a form dynamically.', 'ui' => 'tiny message']);
        $form = \Phlex\Ui\Form::addTo($v);
        $form->addControl('Email');
        $form->onSubmit(function (\Phlex\Ui\Form $form) {
            return $form->success('Subscribed ' . $form->model->get('Email') . ' to newsletter.');
        });

        if ($level < $maxDepth) {
            $addAccordionFunc($v, $maxDepth, $level + 1);
        }
    });

    return $accordion;
};

// add accordion structure
$addAccordionFunc($app);
