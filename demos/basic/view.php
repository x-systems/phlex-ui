<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\HtmlTemplate;
use Phlex\Ui\View;

/** @var \Phlex\Ui\Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

$img = 'https://github.com/atk4/ui/raw/07208a0af84109f0d6e3553e242720d8aeedb784/public/logo.png';

\Phlex\Ui\Header::addTo($webpage, ['Default view has no styling']);
\Phlex\Ui\View::addTo($webpage)->set('just a <div> element');

\Phlex\Ui\Header::addTo($webpage, ['View can specify CSS class']);
\Phlex\Ui\View::addTo($webpage, ['ui' => 'segment', 'raised'])->set('Segment');

\Phlex\Ui\Header::addTo($webpage, ['View can contain stuff']);
\Phlex\Ui\Header::addTo(\Phlex\Ui\View::addTo($webpage, ['ui' => 'segment'])
    ->addClass('inverted red circular'), ['Buy', 'inverted', 'subHeader' => '$' . (random_int(100, 1000) / 100)]);

\Phlex\Ui\Header::addTo($webpage, ['View can use JavaScript']);
\Phlex\Ui\View::addTo($webpage, ['ui' => 'heart rating'])
    ->js(true)->rating(['maxRating' => 5, 'initialRating' => random_int(1, 5)]);

\Phlex\Ui\Header::addTo($webpage, ['View can have events']);
$bb = \Phlex\Ui\View::addTo($webpage, ['ui' => 'large blue buttons']);
$bb->on('click', '.button')->transition('fly up');

foreach (str_split('Click me!!') as $letter) {
    \Phlex\Ui\Button::addTo($bb, [$letter]);
}

\Phlex\Ui\Header::addTo($webpage, ['View load HTML from string or file']);
$planeTemplate = new HtmlTemplate('<div id="{$_id}" class="ui statistic">
    <div class="value">
      <i class="plane icon"></i> {$num}
    </div>
    <div class="label">
      Flights
    </div>
  </div>');
$planeTemplate->set('num', random_int(100, 999));

$plane = \Phlex\Ui\View::addTo($webpage, ['template' => $planeTemplate]);

\Phlex\Ui\Header::addTo($webpage, ['Can be rendered into HTML']);
\Phlex\Ui\View::addTo($webpage, ['ui' => 'segment', 'raised', 'element' => 'pre'])->set($plane->render());

\Phlex\Ui\Header::addTo($webpage, ['Has a unique global identifier']);
\Phlex\Ui\Label::addTo($webpage, ['Plane ID: ', 'detail' => $plane->elementName]);

\Phlex\Ui\Header::addTo($webpage, ['Can interract with JavaScript actions']);
\Phlex\Ui\Button::addTo($webpage, ['Hide plane', 'icon' => 'down arrow'])->on('click', $plane->js()->hide());
\Phlex\Ui\Button::addTo($webpage, ['Show plane', 'icon' => 'up arrow'])->on('click', $plane->js()->show());
\Phlex\Ui\Button::addTo($webpage, ['Jiggle plane', 'icon' => 'expand'])->on('click', $plane->js()->transition('jiggle'));
\Phlex\Ui\Button::addTo($webpage, ['Reload plane', 'icon' => 'refresh'])->on('click', new \Phlex\Ui\JsReload($plane));

\Phlex\Ui\Header::addTo($webpage, ['Can be on a Virtual Page']);
$vp = \Phlex\Ui\VirtualPage::addTo($webpage)->set(function ($page) use ($planeTemplate) {
    $plane = View::addTo($page, ['template' => $planeTemplate]);
    \Phlex\Ui\Label::addTo($page, ['Plane ID: ', 'bottom attached', 'detail' => $plane->elementName]);
});

\Phlex\Ui\Button::addTo($webpage, ['Show $plane in a dialog', 'icon' => 'clone'])->on('click', new \Phlex\Ui\JsModal('Plane Box', $vp));

\Phlex\Ui\Header::addTo($webpage, ['All components extend View (even paginator)']);
$columns = \Phlex\Ui\Columns::addTo($webpage);

\Phlex\Ui\Button::addTo($columns->addColumn(), ['Button'])->addClass('green');
\Phlex\Ui\Header::addTo($columns->addColumn(), ['Header'])->addClass('green');
\Phlex\Ui\Label::addTo($columns->addColumn(), ['Label'])->addClass('green');
\Phlex\Ui\Message::addTo($columns->addColumn(), ['Message'])->addClass('green');
\Phlex\Ui\Paginator::addTo($columns->addColumn(), ['total' => 3, 'reload' => $columns])->addClass('green');

\Phlex\Ui\Header::addTo($webpage, ['Can have a custom render logic']);
\Phlex\Ui\Table::addTo($webpage)->addClass('green')->setSource(['One', 'Two', 'Three']);
