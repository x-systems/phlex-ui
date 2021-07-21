<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

/** @var \Phlex\Ui\Webpage $app */
require_once __DIR__ . '/../init-app.php';

$img = 'https://raw.githubusercontent.com/atk4/ui/2.0.4/public/logo.png';

\Phlex\Ui\Header::addTo($app, ['Labels']);
\Phlex\Ui\Label::addTo($app, ['Hot!']);
\Phlex\Ui\Label::addTo($app, ['23', 'icon' => 'mail']);
\Phlex\Ui\Label::addTo($app, ['new', 'iconRight' => 'delete']);

\Phlex\Ui\Label::addTo($app, ['Coded in PHP', 'image' => $img]);
\Phlex\Ui\Label::addTo($app, ['Number of lines', 'detail' => '33']);

\Phlex\Ui\Header::addTo($app, ['Combinations and Interraction']);
$del = \Phlex\Ui\Label::addTo($app, ['Zoe', 'image' => 'https://semantic-ui.com/images/avatar/small/ade.jpg', 'iconRight' => 'delete']);
$del->on('click', '.delete', $del->js()->fadeOut());

$val = isset($_GET['toggle']) && $_GET['toggle'];
$toggle = \Phlex\Ui\Label::addTo($app, ['icon' => 'toggle ' . ($val ? 'on' : 'off')])->set('Value: ' . $val);
$toggle->on('click', new \Phlex\Ui\JsReload($toggle, ['toggle' => $val ? null : 1]));

$menu = \Phlex\Ui\Menu::addTo($app);
\Phlex\Ui\Label::addTo($menu->addItem('Inbox'), ['20', 'floating red']);
\Phlex\Ui\Label::addTo($menu->addMenu('Others')->addItem('Draft'), ['10', 'floating blue']);

$seg = \Phlex\Ui\View::addTo($app, ['ui' => 'segment']);
\Phlex\Ui\Header::addTo($seg, ['Label Group']);
$labels = \Phlex\Ui\View::addTo($seg, [false, 'tag', 'ui' => 'labels']);
\Phlex\Ui\Label::addTo($seg, ['$9.99']);
\Phlex\Ui\Label::addTo($seg, ['$19.99']);
\Phlex\Ui\Label::addTo($seg, ['$24.99']);

$columns = \Phlex\Ui\Columns::addTo($app);

$c = $columns->addColumn();
$seg = \Phlex\Ui\View::addTo($c, ['ui' => 'raised segment']);
\Phlex\Ui\Label::addTo($seg, ['Left Column', 'top attached', 'icon' => 'book']);
\Phlex\Ui\Label::addTo($seg, ['Lorem', 'red ribbon', 'icon' => 'cut']);
\Phlex\Ui\LoremIpsum::addTo($seg, ['size' => 1]);

$c = $columns->addColumn();
$seg = \Phlex\Ui\View::addTo($c, ['ui' => 'raised segment']);
\Phlex\Ui\Label::addTo($seg, ['Right Column', 'top attached', 'icon' => 'book']);
\Phlex\Ui\LoremIpsum::addTo($seg, ['size' => 1]);
\Phlex\Ui\Label::addTo($seg, ['Ipsum', 'orange bottom right attached', 'icon' => 'cut']);
