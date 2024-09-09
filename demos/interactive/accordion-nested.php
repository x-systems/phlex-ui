<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\Accordion;
use Phlex\Ui\Form;
use Phlex\Ui\Header;
use Phlex\Ui\LoremIpsum;
use Phlex\Ui\Message;
use Phlex\Ui\Webpage;

/** @var Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

/*
\Phlex\Ui\Button::addTo($webpage, ['View Form input split in Accordion section', 'small right floated basic blue', 'iconRight' => 'right arrow'])
    ->link(['accordion-in-form']);
\Phlex\Ui\View::addTo($webpage, ['ui' => 'clearing divider']);
*/

Header::addTo($webpage, ['Nested accordions']);

$addAccordionFunc = static function ($view, $maxDepth = 2, $level = 0) use (&$addAccordionFunc) {
    $accordion = Accordion::addTo($view, ['type' => ['styled', 'fluid']]);

    // static section
    $i1 = $accordion->addSection('Static Text');
    Message::addTo($i1, ['This content is added on page loaded', 'ui' => 'tiny message']);
    LoremIpsum::addTo($i1, ['size' => 1]);
    if ($level < $maxDepth) {
        $addAccordionFunc($i1, $maxDepth, $level + 1);
    }

    // dynamic section - simple view
    $i2 = $accordion->addSection('Dynamic Text', static function ($v) use ($addAccordionFunc, $maxDepth, $level) {
        Message::addTo($v, ['Every time you open this accordion item, you will see a different text', 'ui' => 'tiny message']);
        LoremIpsum::addTo($v, ['size' => 2]);
        if ($level < $maxDepth) {
            $addAccordionFunc($v, $maxDepth, $level + 1);
        }
    });

    // dynamic section - form view
    $i3 = $accordion->addSection('Dynamic Form', static function ($v) use ($addAccordionFunc, $maxDepth, $level) {
        Message::addTo($v, ['Loading a form dynamically.', 'ui' => 'tiny message']);
        $form = Form::addTo($v);
        $form->addControl('Email');
        $form->onSubmit(static function (Form $form) {
            return $form->success('Subscribed ' . $form->model->get('Email') . ' to newsletter.');
        });

        if ($level < $maxDepth) {
            $addAccordionFunc($v, $maxDepth, $level + 1);
        }
    });

    return $accordion;
};

// add accordion structure
$addAccordionFunc($webpage);
