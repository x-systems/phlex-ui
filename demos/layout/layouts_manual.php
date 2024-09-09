<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\Layout;
use Phlex\Ui\Lister;
use Phlex\Ui\Text;
use Phlex\Ui\Webpage;

/** @var Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

$layout = new Layout(['defaultTemplate' => __DIR__ . '/templates/layout1.html']);

Lister::addTo($layout, [], ['Report'])
    ->setModel(new SomeData());

$webpage->initBody([Layout::class]);

Text::addTo($webpage)->addHtml($layout->render());
