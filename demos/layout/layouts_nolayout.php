<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

/** @var \Phlex\Ui\App $app */
require_once __DIR__ . '/../init-app.php';

// nothing to do with Agile UI - will not use any Layout
$a = new \Phlex\Ui\LoremIpsum();
$text = $a->generateLorem(150);

$app->html = null;
$app->initLayout([\Phlex\Ui\Layout::class]);

\Phlex\Ui\Text::addTo($app->layout)->addParagraph($text);
