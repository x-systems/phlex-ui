<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\Button;
use Phlex\Ui\Columns;
use Phlex\Ui\Header;
use Phlex\Ui\HtmlTemplate;
use Phlex\Ui\JsModal;
use Phlex\Ui\JsReload;
use Phlex\Ui\Label;
use Phlex\Ui\Message;
use Phlex\Ui\Paginator;
use Phlex\Ui\Table;
use Phlex\Ui\View;
use Phlex\Ui\VirtualPage;
use Phlex\Ui\Webpage;

/** @var Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

$img = 'https://github.com/atk4/ui/raw/07208a0af84109f0d6e3553e242720d8aeedb784/public/logo.png';

Header::addTo($webpage, ['Default view has no styling']);
View::addTo($webpage)->set('just a <div> element');

Header::addTo($webpage, ['View can specify CSS class']);
View::addTo($webpage, ['ui' => 'segment', 'raised'])->set('Segment');

Header::addTo($webpage, ['View can contain stuff']);
Header::addTo(View::addTo($webpage, ['ui' => 'segment'])
    ->addClass('inverted red circular'), ['Buy', 'inverted', 'subHeader' => '$' . (random_int(100, 1000) / 100)]);

Header::addTo($webpage, ['View can use JavaScript']);
View::addTo($webpage, ['ui' => 'heart rating'])
    ->js(true)->rating(['maxRating' => 5, 'initialRating' => random_int(1, 5)]);

Header::addTo($webpage, ['View can have events']);
$bb = View::addTo($webpage, ['ui' => 'large blue buttons']);
$bb->on('click', '.button')->transition('fly up');

foreach (str_split('Click me!!') as $letter) {
    Button::addTo($bb, [$letter]);
}

Header::addTo($webpage, ['View load HTML from string or file']);
$planeTemplate = new HtmlTemplate('<div id="{$_id}" class="ui statistic">
    <div class="value">
      <i class="plane icon"></i> {$num}
    </div>
    <div class="label">
      Flights
    </div>
  </div>');
$planeTemplate->set('num', random_int(100, 999));

$plane = View::addTo($webpage, ['template' => $planeTemplate]);

Header::addTo($webpage, ['Can be rendered into HTML']);
View::addTo($webpage, ['ui' => 'segment', 'raised', 'element' => 'pre'])->set($plane->render());

Header::addTo($webpage, ['Has a unique global identifier']);
Label::addTo($webpage, ['Plane ID: ', 'detail' => $plane->elementName]);

Header::addTo($webpage, ['Can interract with JavaScript actions']);
Button::addTo($webpage, ['Hide plane', 'icon' => 'down arrow'])->on('click', $plane->js()->hide());
Button::addTo($webpage, ['Show plane', 'icon' => 'up arrow'])->on('click', $plane->js()->show());
Button::addTo($webpage, ['Jiggle plane', 'icon' => 'expand'])->on('click', $plane->js()->transition('jiggle'));
Button::addTo($webpage, ['Reload plane', 'icon' => 'refresh'])->on('click', new JsReload($plane));

Header::addTo($webpage, ['Can be on a Virtual Page']);
$vp = VirtualPage::addTo($webpage)->set(static function ($page) use ($planeTemplate) {
    $plane = View::addTo($page, ['template' => $planeTemplate]);
    Label::addTo($page, ['Plane ID: ', 'bottom attached', 'detail' => $plane->elementName]);
});

Button::addTo($webpage, ['Show $plane in a dialog', 'icon' => 'clone'])->on('click', new JsModal('Plane Box', $vp));

Header::addTo($webpage, ['All components extend View (even paginator)']);
$columns = Columns::addTo($webpage);

Button::addTo($columns->addColumn(), ['Button'])->addClass('green');
Header::addTo($columns->addColumn(), ['Header'])->addClass('green');
Label::addTo($columns->addColumn(), ['Label'])->addClass('green');
Message::addTo($columns->addColumn(), ['Message'])->addClass('green');
Paginator::addTo($columns->addColumn(), ['total' => 3, 'reload' => $columns])->addClass('green');

Header::addTo($webpage, ['Can have a custom render logic']);
Table::addTo($webpage)->addClass('green')->setSource(['One', 'Two', 'Three']);
