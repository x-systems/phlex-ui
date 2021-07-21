<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

/** @var \Phlex\Ui\Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

$country = new CountryLock($webpage->db);
DemoActionsUtil::setupDemoActions($country);

\Phlex\Ui\Header::addTo($webpage, ['Right Panel', 'subHeader' => 'Content on the fly!']);

// PANEL

\Phlex\Ui\Header::addTo($webpage, ['Static', 'size' => 4, 'subHeader' => 'Panel may have static content only.']);
$panel = $webpage->body->addRightPanel(new \Phlex\Ui\Panel\Right(['dynamic' => false]));
\Phlex\Ui\Message::addTo($panel, ['This panel contains only static content.']);
$btn = \Phlex\Ui\Button::addTo($webpage, ['Open Static']);
$btn->on('click', $panel->jsOpen());
\Phlex\Ui\View::addTo($webpage, ['ui' => 'divider']);

// PANEL_1

\Phlex\Ui\Header::addTo($webpage, ['Dynamic', 'size' => 4, 'subHeader' => 'Panel can load content dynamically']);
$panel1 = $webpage->body->addRightPanel(new \Phlex\Ui\Panel\Right());
\Phlex\Ui\Message::addTo($panel1, ['This panel will load content dynamically below according to button select on the right.']);
$btn = \Phlex\Ui\Button::addTo($webpage, ['Button 1']);
$btn->js(true)->data('btn', '1');
$btn->on('click', $panel1->jsOpen(['btn'], 'orange'));

$btn = \Phlex\Ui\Button::addTo($webpage, ['Button 2']);
$btn->js(true)->data('btn', '2');
$btn->on('click', $panel1->jsOpen(['btn'], 'orange'));

$view = \Phlex\Ui\View::addTo($webpage, ['ui' => 'segment']);
$text = \Phlex\Ui\Text::addTo($view);
$text->set($_GET['txt'] ?? 'Not Complete');

$panel1->onOpen(function ($p) use ($view) {
    $panel = \Phlex\Ui\View::addTo($p, ['ui' => 'basic segment']);
    $buttonNumber = $panel->stickyGet('btn');

    $panelText = 'You loaded panel content using button #' . $buttonNumber;
    \Phlex\Ui\Message::addTo($panel, ['Panel 1', 'text' => $panelText]);

    $reloadPanelButton = \Phlex\Ui\Button::addTo($panel, ['Reload Myself']);
    $reloadPanelButton->on('click', new \Phlex\Ui\JsReload($panel));

    \Phlex\Ui\View::addTo($panel, ['ui' => 'divider']);
    $panelButton = \Phlex\Ui\Button::addTo($panel, ['Complete']);
    $panelButton->on('click', [
        $p->getOwner()->jsClose(),
        new \Phlex\Ui\JsReload($view, ['txt' => 'Complete using button #' . $buttonNumber]),
    ]);
});

\Phlex\Ui\View::addTo($webpage, ['ui' => 'divider']);

// PANEL_2

\Phlex\Ui\Header::addTo($webpage, ['Closing option', 'size' => 4, 'subHeader' => 'Panel can prevent from closing.']);

$panel2 = $webpage->body->addRightPanel(new \Phlex\Ui\Panel\Right(['hasClickAway' => false]));
$icon = \Phlex\Ui\Icon::addTo($webpage, ['big cog'])->addStyle('cursor', 'pointer');
$icon->on('click', $panel2->jsOpen());
$panel2->addConfirmation('Changes will be lost. Are you sure?');

$msg = \Phlex\Ui\Message::addTo($panel2, ['Prevent close.']);

$txt = \Phlex\Ui\Text::addTo($msg);
$txt->addParagraph('This panel can only be closed via it\'s close icon at top right.');
$txt->addParagraph('Try to change dropdown value and close without saving!');

$panel2->onOpen(function ($p) {
    $form = \Phlex\Ui\Form::addTo($p);
    $form->addHeader('Settings');
    $form->addControl('name', [\Phlex\Ui\Form\Control\Dropdown::class, 'values' => ['1' => 'Option 1', '2' => 'Option 2']])
        ->set('1')
        ->onChange($p->getOwner()->jsDisplayWarning(true));

    $form->onSubmit(function (\Phlex\Ui\Form $form) use ($p) {
        return [
            new \Phlex\Ui\JsToast('Saved, closing panel.'),
            $p->getOwner()->jsDisplayWarning(false),
            $p->getOwner()->jsClose(),
        ];
    });
});
\Phlex\Ui\View::addTo($webpage, ['ui' => 'divider']);

// PANEL_3

$countryId = $webpage->stickyGet('id');
\Phlex\Ui\Header::addTo($webpage, ['UserAction Friendly', 'size' => 4, 'subHeader' => 'Panel can run model action.']);
$panel3 = $webpage->body->addRightPanel(new \Phlex\Ui\Panel\Right());
$msg = \Phlex\Ui\Message::addTo($panel3, ['Run Country model action below.']);

$deck = \Phlex\Ui\View::addTo($webpage, ['ui' => 'cards']);
$country->setLimit(3);

foreach ($country as $ct) {
    $c = \Phlex\Ui\Card::addTo($deck, ['useLabel' => true])->addStyle('cursor', 'pointer');
    $c->setModel($ct);
    $c->on('click', $panel3->jsOpen(['id'], 'orange'));
}

$panel3->onOpen(function ($p) use ($country, $countryId) {
    $seg = \Phlex\Ui\View::addTo($p, ['ui' => 'basic segment center aligned']);
    \Phlex\Ui\Header::addTo($seg, [$country->load($countryId)->getTitle()]);
    $buttons = \Phlex\Ui\View::addTo($seg, ['ui' => 'vertical basic buttons']);
    foreach ($country->getUserActions() as $action) {
        $button = \Phlex\Ui\Button::addTo($buttons, [$action->getCaption()]);
        $button->on('click', $action, ['args' => ['id' => $countryId]]);
    }
});
