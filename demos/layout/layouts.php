<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\Button;
use Phlex\Ui\Layout\Centered;
use Phlex\Ui\Text;
use Phlex\Ui\View;
use Phlex\Ui\Webpage;

/** @var Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

// buttons configuration: [page, title]
$buttons = [
    ['page' => ['layouts_nolayout'], 'title' => 'HTML without layout'],
    ['page' => ['layouts_manual'], 'title' => 'Manual layout'],
    ['page' => ['../basic/header', 'layout' => Centered::class], 'title' => 'Centered layout'],
    ['page' => ['layouts_admin'], 'title' => 'Admin Layout'],
    ['page' => ['layouts_error'], 'title' => 'Exception Error'],
];

// layout
Text::addTo(View::addTo($webpage, ['red' => true, 'ui' => 'segment']))
    ->addParagraph('Layouts can be used to wrap your UI elements into HTML / Boilerplate');

// toolbar
$tb = View::addTo($webpage);

// iframe
$i = View::addTo($webpage, ['green' => true, 'ui' => 'segment'])->setElement('iframe')->setStyle(['width' => '100%', 'height' => '500px']);

// add buttons in toolbar
foreach ($buttons as $k => $args) {
    Button::addTo($tb)
        ->set([$args['title'], 'iconRight' => 'down arrow'])
        ->js('click', $i->js()->attr('src', $webpage->url($args['page'])));
}
