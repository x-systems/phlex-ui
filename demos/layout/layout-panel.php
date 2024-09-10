<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\Button;
use Phlex\Ui\Card;
use Phlex\Ui\Form;
use Phlex\Ui\Form\Control\Dropdown;
use Phlex\Ui\Header;
use Phlex\Ui\Icon;
use Phlex\Ui\JsReload;
use Phlex\Ui\JsToast;
use Phlex\Ui\Message;
use Phlex\Ui\Panel\Right;
use Phlex\Ui\Text;
use Phlex\Ui\View;
use Phlex\Ui\Webpage;

/** @var Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

$country = new CountryLock($webpage->db);
DemoActionsUtil::setupDemoActions($country);

Header::addTo($webpage, ['Right Panel', 'subHeader' => 'Content on the fly!']);

// PANEL

Header::addTo($webpage, ['Static', 'size' => 4, 'subHeader' => 'Panel may have static content only.']);
$panel = $webpage->body->addRightPanel(new Right(['dynamic' => false]));
Message::addTo($panel, ['This panel contains only static content.']);
$btn = Button::addTo($webpage, ['Open Static']);
$btn->on('click', $panel->jsOpen());
View::addTo($webpage, ['ui' => 'divider']);

// PANEL_1

Header::addTo($webpage, ['Dynamic', 'size' => 4, 'subHeader' => 'Panel can load content dynamically']);
$panel1 = $webpage->body->addRightPanel(new Right());
Message::addTo($panel1, ['This panel will load content dynamically below according to button select on the right.']);
$btn = Button::addTo($webpage, ['Button 1']);
$btn->js(true)->data('btn', '1');
$btn->on('click', $panel1->jsOpen(['btn'], 'orange'));

$btn = Button::addTo($webpage, ['Button 2']);
$btn->js(true)->data('btn', '2');
$btn->on('click', $panel1->jsOpen(['btn'], 'orange'));

$view = View::addTo($webpage, ['ui' => 'segment']);
$text = Text::addTo($view);
$text->set($_GET['txt'] ?? 'Not Complete');

$panel1->onOpen(function ($p) use ($view) {
    $panel = View::addTo($p, ['ui' => 'basic segment']);
    $buttonNumber = $panel->stickyGet('btn');

    $panelText = 'You loaded panel content using button #' . $buttonNumber;
    Message::addTo($panel, ['Panel 1', 'text' => $panelText]);

    $reloadPanelButton = Button::addTo($panel, ['Reload Myself']);
    $reloadPanelButton->on('click', new JsReload($panel));

    View::addTo($panel, ['ui' => 'divider']);
    $panelButton = Button::addTo($panel, ['Complete']);
    $panelButton->on('click', [
        $p->getOwner()->jsClose(),
        new JsReload($view, ['txt' => 'Complete using button #' . $buttonNumber]),
    ]);
});

View::addTo($webpage, ['ui' => 'divider']);

// PANEL_2

Header::addTo($webpage, ['Closing option', 'size' => 4, 'subHeader' => 'Panel can prevent from closing.']);

$panel2 = $webpage->body->addRightPanel(new Right(['hasClickAway' => false]));
$icon = Icon::addTo($webpage, ['big cog'])->addStyle('cursor', 'pointer');
$icon->on('click', $panel2->jsOpen());
$panel2->addConfirmation('Changes will be lost. Are you sure?');

$msg = Message::addTo($panel2, ['Prevent close.']);

$txt = Text::addTo($msg);
$txt->addParagraph('This panel can only be closed via it\'s close icon at top right.');
$txt->addParagraph('Try to change dropdown value and close without saving!');

$panel2->onOpen(function ($p) {
    $form = Form::addTo($p);
    $form->addHeader('Settings');
    $form->addControl('name', [Dropdown::class, 'values' => ['1' => 'Option 1', '2' => 'Option 2']])
        ->set('1')
        ->onChange($p->getOwner()->jsDisplayWarning(true));

    $form->onSubmit(function (Form $form) use ($p) {
        return [
            new JsToast('Saved, closing panel.'),
            $p->getOwner()->jsDisplayWarning(false),
            $p->getOwner()->jsClose(),
        ];
    });
});
View::addTo($webpage, ['ui' => 'divider']);

// PANEL_3

$countryId = $webpage->stickyGet('id');
Header::addTo($webpage, ['UserAction Friendly', 'size' => 4, 'subHeader' => 'Panel can run model action.']);
$panel3 = $webpage->body->addRightPanel(new Right());
$msg = Message::addTo($panel3, ['Run Country model action below.']);

$deck = View::addTo($webpage, ['ui' => 'cards']);
$country->setLimit(3);

foreach ($country as $ct) {
    $c = Card::addTo($deck, ['useLabel' => true])->addStyle('cursor', 'pointer');
    $c->setModel($ct);
    $c->on('click', $panel3->jsOpen(['id'], 'orange'));
}

$panel3->onOpen(function ($p) use ($country, $countryId) {
    $seg = View::addTo($p, ['ui' => 'basic segment center aligned']);
    Header::addTo($seg, [$country->load($countryId)->getTitle()]);
    $buttons = View::addTo($seg, ['ui' => 'vertical basic buttons']);
    foreach ($country->getUserActions() as $action) {
        $button = Button::addTo($buttons, [$action->getCaption()]);
        $button->on('click', $action, ['args' => ['id' => $countryId]]);
    }
});
