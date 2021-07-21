<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

/** @var \Phlex\Ui\Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

// create layout
$gridLayout = \Phlex\Ui\GridLayout::addTo($webpage, ['columns' => 4, 'rows' => 2]);

// add other views in layout spots
\Phlex\Ui\LoremIpsum::addTo($gridLayout, ['words' => 4], ['r1c1']); // row 1, col 1
\Phlex\Ui\LoremIpsum::addTo($gridLayout, ['words' => 4], ['r1c4']); // row 1, col 4
\Phlex\Ui\LoremIpsum::addTo($gridLayout, ['words' => 4], ['r2c2']); // row 2, col 2
