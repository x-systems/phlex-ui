<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\Button;
use Phlex\Ui\Header;
use Phlex\Ui\JsModal;
use Phlex\Ui\LoremIpsum;
use Phlex\Ui\Message;
use Phlex\Ui\Table;
use Phlex\Ui\Text;
use Phlex\Ui\View;
use Phlex\Ui\VirtualPage;
use Phlex\Ui\Webpage;

/** @var Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

// Demonstrate the use of Virtual Page.

// define virtual page.
$virtualPage = VirtualPage::addTo($webpage->body, ['urlTrigger' => 'in']);

// Add content to virtual page.
if (isset($_GET['p_id'])) {
    Header::addTo($virtualPage, [$_GET['p_id']])->addClass('__phlex-behat-test-car');
}
LoremIpsum::addTo($virtualPage, ['size' => 1]);
$virtualPageButton = Button::addTo($virtualPage, ['Back', 'icon' => 'left arrow']);
$virtualPageButton->link('virtual.php');
$virtualPage->ui = 'grey inverted segment';

$msg = Message::addTo($webpage, ['Virtual Page']);
$msg->text->addParagraph('Virtual page content are not rendered on page load. They will ouptput their content when trigger.');
$msg->text->addParagraph('Click button below to trigger it.');

// button that trigger virtual page.
$btn = Button::addTo($webpage, ['More info on Car']);
$btn->link($virtualPage->cb->getUrl() . '&p_id=Car');

$btn = Button::addTo($webpage, ['More info on Bike']);
$btn->link($virtualPage->cb->getUrl() . '&p_id=Bike');

// Test 1 - Basic reloading
Header::addTo($webpage, ['Virtual Page Logic']);

$virtualPage = VirtualPage::addTo($webpage); // this page will not be visible unless you trigger it specifically
View::addTo($virtualPage, ['Contents of your pop-up here'])->addClass('ui header __phlex-behat-test-content');
LoremIpsum::addTo($virtualPage, ['size' => 2]);

Counter::addTo($virtualPage);
View::addTo($virtualPage, ['ui' => 'hidden divider']);
Button::addTo($virtualPage, ['Back', 'icon' => 'left arrow'])->link('virtual.php');

$bar = View::addTo($webpage, ['ui' => 'buttons']);
Button::addTo($bar)->set('Inside current layout')->link($virtualPage->getUrl());
Button::addTo($bar)->set('On a blank page')->link($virtualPage->getUrl('popup'));
Button::addTo($bar)->set('No layout at all')->link($virtualPage->getUrl('cut'));

Header::addTo($webpage, ['Inside Modal', 'subHeader' => 'Virtual page content can be display using JsModal Class.']);

$bar = View::addTo($webpage, ['ui' => 'buttons']);
Button::addTo($bar)->set('Load in Modal')->on('click', new JsModal('My Popup Title', $virtualPage->getJsUrl('cut')));

Button::addTo($bar)->set('Simulate slow load')->on('click', new JsModal('My Popup Title', $virtualPage->getJsUrl('cut') . '&slow=true'));
if (isset($_GET['slow'])) {
    sleep(1);
}

Button::addTo($bar)->set('No title')->on('click', new JsModal(null, $virtualPage->getJsUrl('cut')));

View::addTo($webpage, ['ui' => 'hidden divider']);
$text = Text::addTo($webpage);
$text->addParagraph('Can also be trigger from a js event, like clicking on a table row.');
$table = Table::addTo($webpage, ['celled' => true]);
$table->setModel(new SomeData());

$frame = VirtualPage::addTo($webpage);
$frame->set(static function ($frame) {
    Header::addTo($frame, ['Clicked row with ID = ' . ($_GET['id'] ?? '')]);
});

$table->onRowClick(new JsModal('Row Clicked', $frame, ['id' => $table->jsRow()->data('id')]));
