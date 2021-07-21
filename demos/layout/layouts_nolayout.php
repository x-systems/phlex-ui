<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

/** @var \Phlex\Ui\Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

// nothing to do with Agile UI - will not use any Layout
$a = new \Phlex\Ui\LoremIpsum();
$text = $a->generateLorem(150);

$webpage->initBody([\Phlex\Ui\Layout::class]);

\Phlex\Ui\Text::addTo($webpage)->addParagraph($text);
