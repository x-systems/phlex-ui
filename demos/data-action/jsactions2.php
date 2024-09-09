<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\Button;
use Phlex\Ui\Card;
use Phlex\Ui\GridLayout;
use Phlex\Ui\Header;
use Phlex\Ui\Message;
use Phlex\Ui\View;
use Phlex\Ui\Webpage;

/** @var Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

// Demo for Model action

$country = new CountryLock($webpage->db);
$entity = $country->tryLoadAny();
$countryId = $entity->getId();

// Model actions for this file are setup in DemoActionUtil.
DemoActionsUtil::setupDemoActions($country);

Header::addTo($webpage, ['Assign Model action to button event', 'subHeader' => 'Execute model action on this country record by clicking on the appropriate button on the right.']);

$msg = Message::addTo($webpage, ['Notes', 'type' => 'info']);
$msg->text->addParagraph('When passing an action to a button event, Ui will determine what executor is required base on the action properties.');
$msg->text->addParagraph('If action require arguments, fields and/or preview, then a ModalExecutor will be use.');

View::addTo($webpage, ['ui' => 'ui clearing divider']);

$gl = GridLayout::addTo($webpage, ['rows' => 1, 'columns' => 2]);
$c = Card::addTo($gl, ['useLabel' => true], ['r1c1']);
$c->addContent(new Header(['Using country: ']));
$c->setModel($entity, [$country->key()->iso, $country->key()->iso3, $country->key()->phonecode]);

$buttons = View::addTo($gl, ['ui' => 'vertical basic buttons'], ['r1c2']);

// Create a button for every action in Country model.
foreach ($country->getUserActions() as $action) {
    $b = Button::addTo($buttons, [$action->getCaption()]);
    // Assign action to button using current model id as url arguments.
    $b->on('click', $action, ['args' => ['id' => $countryId]]);
}
