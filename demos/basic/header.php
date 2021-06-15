<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

/** @var \Phlex\Ui\App $app */
require_once __DIR__ . '/../init-app.php';

$img = 'https://raw.githubusercontent.com/atk4/ui/2.0.4/public/logo.png';

$seg = \Phlex\Ui\View::addTo($app, ['ui' => 'segment']);
\Phlex\Ui\Header::addTo($seg, ['H1 Header', 'size' => 1]);
\Phlex\Ui\Header::addTo($seg, ['H2 Header', 'size' => 2]);
\Phlex\Ui\Header::addTo($seg, ['H3 Header', 'size' => 3]);
\Phlex\Ui\Header::addTo($seg, ['H4 Header', 'size' => 4]);
\Phlex\Ui\Header::addTo($seg, ['H5 Header', 'size' => 5, 'dividing']);
\Phlex\Ui\View::addTo($seg, ['element' => 'P'])->set('This is a following paragraph of text');

\Phlex\Ui\Header::addTo($seg, ['H1', 'size' => 1, 'subHeader' => 'H1 subheader']);
\Phlex\Ui\Header::addTo($seg, ['H5', 'size' => 5, 'subHeader' => 'H5 subheader']);

$seg = \Phlex\Ui\View::addTo($app, ['ui' => 'segment']);
\Phlex\Ui\Header::addTo($seg, ['Huge Header', 'size' => 'huge']);
\Phlex\Ui\Header::addTo($seg, ['Large Header', 'size' => 'large']);
\Phlex\Ui\Header::addTo($seg, ['Medium Header', 'size' => 'medium']);
\Phlex\Ui\Header::addTo($seg, ['Small Header', 'size' => 'small']);
\Phlex\Ui\Header::addTo($seg, ['Tiny Header', 'size' => 'tiny']);

\Phlex\Ui\Header::addTo($seg, ['Sub Header', 'sub']);

$seg = \Phlex\Ui\View::addTo($app, ['ui' => 'segment']);
\Phlex\Ui\Header::addTo($seg, ['Header with icon', 'icon' => 'settings']);
\Phlex\Ui\Header::addTo($seg, ['Header with icon', 'icon' => 'settings', 'subHeader' => 'and with sub-header']);
\Phlex\Ui\Header::addTo($seg, ['Header with image', 'image' => $img, 'subHeader' => 'and with sub-header']);

$seg = \Phlex\Ui\View::addTo($app, ['ui' => 'segment']);
\Phlex\Ui\Header::addTo($seg, ['Center-aligned', 'aligned' => 'center', 'icon' => 'settings', 'subHeader' => 'header with icon']);

$seg = \Phlex\Ui\View::addTo($app, ['ui' => 'segment']);
\Phlex\Ui\Header::addTo($seg, ['Center-aligned', 'aligned' => 'center', 'icon' => 'circular users', 'subHeader' => 'header with icon']);

$seg = \Phlex\Ui\View::addTo($app, ['ui' => 'segment']);
\Phlex\Ui\Header::addTo($seg, ['Center-aligned', 'aligned' => 'center', 'image' => $img, 'subHeader' => 'header with image']);

$seg = \Phlex\Ui\View::addTo($app, ['ui' => 'segment']);
\Phlex\Ui\Header::addTo($seg, ['Center-aligned', 'aligned' => 'center', 'image' => [$img, 'disabled'], 'subHeader' => 'header with image']);
