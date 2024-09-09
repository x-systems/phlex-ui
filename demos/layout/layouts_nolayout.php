<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\Layout;
use Phlex\Ui\LoremIpsum;
use Phlex\Ui\Text;
use Phlex\Ui\Webpage;

/** @var Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

// nothing to do with Phlex UI - will not use any Layout
$a = new LoremIpsum();
$text = $a->generateLorem(150);

$webpage->initBody([Layout::class]);

Text::addTo($webpage)->addParagraph($text);
