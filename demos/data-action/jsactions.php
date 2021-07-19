<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Data\Model\UserAction;
use Phlex\Ui\Form\Control\Line;
use Phlex\Ui\UserAction\JsCallbackExecutor;

/** @var \Phlex\Ui\App $app */
require_once __DIR__ . '/../init-app.php';

\Phlex\Ui\Header::addTo($app, [
    'Extensions to ATK Data Actions',
    'subHeader' => 'Model action can be trigger in various ways.',
]);

// Model action setup.
$country = new Country($app->db);

$sendEmailAction = $country->addUserAction('Email', [
    'confirmation' => 'Are you sure you wish to send an email?',
    'callback' => function (Country $country) {
        return 'Email to Kristy in ' . $country->name . ' has been sent!';
    },
]);

///////////////////////////////////////////

\Phlex\Ui\View::addTo($app, ['ui' => 'ui clearing divider']);

\Phlex\Ui\Header::addTo($app, [
    'Using Input button',
    'size' => 4,
    'subHeader' => 'Action can be triggered via a button attached to an input. The data action argument value is set to the input value.',
]);

// Note here that we explicitly required a JsCallbackExecutor for the greet action.
$country->addUserAction('greet', [
    'appliesTo' => UserAction::APPLIES_TO_NO_RECORDS,
    'args' => [
        'name' => [
            'type' => 'string',
            'required' => true,
        ],
    ],
    'callback' => function (Country $model, $name) {
        return 'Hello ' . $name;
    },
]);

// Set the action property for the Line Form Control.
Line::addTo($app, ['action' => $country->getUserAction('greet')]);

///////////////////////////////////////////

\Phlex\Ui\View::addTo($app, ['ui' => 'ui clearing divider']);

\Phlex\Ui\Header::addTo($app, [
    'Using buttons in a Card component',
    'size' => 4,
    'subHeader' => 'Easily trigger a data action using a Card component.',
]);

// Card component.
$card = \Phlex\Ui\Card::addTo($app);
$content = new \Phlex\Ui\View(['class' => ['content']]);
$content->addView($img = new \Phlex\Ui\Image(['../images/kristy.png']));
$img->addClass('right floated mini ui image');
$content->addView(new \Phlex\Ui\Header(['Kristy']));

$card->addContent($content);
$card->addDescription('Kristy is a friend of Mully.');

$s = $card->addSection('Country');
$s->addFields($entity = $country->loadAny(), [$country->key()->name, $country->key()->iso]);

// Pass the model action to the Card::addClickAction() method.
$card->addClickAction($sendEmailAction, null, ['id' => $entity->getId()]);
