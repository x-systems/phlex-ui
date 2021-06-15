<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

/** @var \Phlex\Ui\App $app */
require_once __DIR__ . '/../init-app.php';

// Demonstrate the use of Virtual Page.

// define virtual page.
$virtualPage = \Phlex\Ui\VirtualPage::addTo($app->layout, ['urlTrigger' => 'in']);

// Add content to virtual page.
if (isset($_GET['p_id'])) {
    \Phlex\Ui\Header::addTo($virtualPage, [$_GET['p_id']])->addClass('__atk-behat-test-car');
}
\Phlex\Ui\LoremIpsum::addTo($virtualPage, ['size' => 1]);
$virtualPageButton = \Phlex\Ui\Button::addTo($virtualPage, ['Back', 'icon' => 'left arrow']);
$virtualPageButton->link('virtual.php');
$virtualPage->ui = 'grey inverted segment';

$msg = \Phlex\Ui\Message::addTo($app, ['Virtual Page']);
$msg->text->addParagraph('Virtual page content are not rendered on page load. They will ouptput their content when trigger.');
$msg->text->addParagraph('Click button below to trigger it.');

// button that trigger virtual page.
$btn = \Phlex\Ui\Button::addTo($app, ['More info on Car']);
$btn->link($virtualPage->cb->getUrl() . '&p_id=Car');

$btn = \Phlex\Ui\Button::addTo($app, ['More info on Bike']);
$btn->link($virtualPage->cb->getUrl() . '&p_id=Bike');

// Test 1 - Basic reloading
\Phlex\Ui\Header::addTo($app, ['Virtual Page Logic']);

$virtualPage = \Phlex\Ui\VirtualPage::addTo($app); // this page will not be visible unless you trigger it specifically
\Phlex\Ui\View::addTo($virtualPage, ['Contents of your pop-up here'])->addClass('ui header __atk-behat-test-content');
\Phlex\Ui\LoremIpsum::addTo($virtualPage, ['size' => 2]);

Counter::addTo($virtualPage);
\Phlex\Ui\View::addTo($virtualPage, ['ui' => 'hidden divider']);
\Phlex\Ui\Button::addTo($virtualPage, ['Back', 'icon' => 'left arrow'])->link('virtual.php');

$bar = \Phlex\Ui\View::addTo($app, ['ui' => 'buttons']);
\Phlex\Ui\Button::addTo($bar)->set('Inside current layout')->link($virtualPage->getUrl());
\Phlex\Ui\Button::addTo($bar)->set('On a blank page')->link($virtualPage->getUrl('popup'));
\Phlex\Ui\Button::addTo($bar)->set('No layout at all')->link($virtualPage->getUrl('cut'));

\Phlex\Ui\Header::addTo($app, ['Inside Modal', 'subHeader' => 'Virtual page content can be display using JsModal Class.']);

$bar = \Phlex\Ui\View::addTo($app, ['ui' => 'buttons']);
\Phlex\Ui\Button::addTo($bar)->set('Load in Modal')->on('click', new \Phlex\Ui\JsModal('My Popup Title', $virtualPage->getJsUrl('cut')));

\Phlex\Ui\Button::addTo($bar)->set('Simulate slow load')->on('click', new \Phlex\Ui\JsModal('My Popup Title', $virtualPage->getJsUrl('cut') . '&slow=true'));
if (isset($_GET['slow'])) {
    sleep(1);
}

\Phlex\Ui\Button::addTo($bar)->set('No title')->on('click', new \Phlex\Ui\JsModal(null, $virtualPage->getJsUrl('cut')));

\Phlex\Ui\View::addTo($app, ['ui' => 'hidden divider']);
$text = \Phlex\Ui\Text::addTo($app);
$text->addParagraph('Can also be trigger from a js event, like clicking on a table row.');
$table = \Phlex\Ui\Table::addTo($app, ['celled' => true]);
$table->setModel(new SomeData());

$frame = \Phlex\Ui\VirtualPage::addTo($app);
$frame->set(function ($frame) {
    \Phlex\Ui\Header::addTo($frame, ['Clicked row with ID = ' . ($_GET['id'] ?? '')]);
});

$table->onRowClick(new \Phlex\Ui\JsModal('Row Clicked', $frame, ['id' => $table->jsRow()->data('id')]));
