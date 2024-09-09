<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\Columns;
use Phlex\Ui\Header;
use Phlex\Ui\JsReload;
use Phlex\Ui\Label;
use Phlex\Ui\LoremIpsum;
use Phlex\Ui\Menu;
use Phlex\Ui\View;
use Phlex\Ui\Webpage;

/** @var Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

$img = 'https://raw.githubusercontent.com/atk4/ui/2.0.4/public/logo.png';

Header::addTo($webpage, ['Labels']);
Label::addTo($webpage, ['Hot!']);
Label::addTo($webpage, ['23', 'icon' => 'mail']);
Label::addTo($webpage, ['new', 'iconRight' => 'delete']);

Label::addTo($webpage, ['Coded in PHP', 'image' => $img]);
Label::addTo($webpage, ['Number of lines', 'detail' => '33']);

Header::addTo($webpage, ['Combinations and Interraction']);
$del = Label::addTo($webpage, ['Zoe', 'image' => 'https://semantic-ui.com/images/avatar/small/ade.jpg', 'iconRight' => 'delete']);
$del->on('click', '.delete', $del->js()->fadeOut());

$val = isset($_GET['toggle']) && $_GET['toggle'];
$toggle = Label::addTo($webpage, ['icon' => 'toggle ' . ($val ? 'on' : 'off')])->set('Value: ' . $val);
$toggle->on('click', new JsReload($toggle, ['toggle' => $val ? null : 1]));

$menu = Menu::addTo($webpage);
Label::addTo($menu->addItem('Inbox'), ['20', 'floating red']);
Label::addTo($menu->addMenu('Others')->addItem('Draft'), ['10', 'floating blue']);

$seg = View::addTo($webpage, ['ui' => 'segment']);
Header::addTo($seg, ['Label Group']);
$labels = View::addTo($seg, [false, 'tag', 'ui' => 'labels']);
Label::addTo($seg, ['$9.99']);
Label::addTo($seg, ['$19.99']);
Label::addTo($seg, ['$24.99']);

$columns = Columns::addTo($webpage);

$c = $columns->addColumn();
$seg = View::addTo($c, ['ui' => 'raised segment']);
Label::addTo($seg, ['Left Column', 'top attached', 'icon' => 'book']);
Label::addTo($seg, ['Lorem', 'red ribbon', 'icon' => 'cut']);
LoremIpsum::addTo($seg, ['size' => 1]);

$c = $columns->addColumn();
$seg = View::addTo($c, ['ui' => 'raised segment']);
Label::addTo($seg, ['Right Column', 'top attached', 'icon' => 'book']);
LoremIpsum::addTo($seg, ['size' => 1]);
Label::addTo($seg, ['Ipsum', 'orange bottom right attached', 'icon' => 'cut']);
