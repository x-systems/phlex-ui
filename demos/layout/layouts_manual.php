<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

/** @var \Phlex\Ui\Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

$layout = new \Phlex\Ui\Layout(['defaultTemplate' => __DIR__ . '/templates/layout1.html']);

\Phlex\Ui\Lister::addTo($layout, [], ['Report'])
    ->setModel(new SomeData());

$webpage->html = null;
$webpage->initLayout([\Phlex\Ui\Layout::class]);

\Phlex\Ui\Text::addTo($webpage->layout)->addHtml($layout->render());
