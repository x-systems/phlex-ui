<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\Button;

/** @var \Phlex\Ui\App $app */
require_once __DIR__ . '/../init-app.php';

\Phlex\Ui\Button::addTo($app, ['Card', 'small left floated basic blue', 'icon' => 'left arrow'])
    ->link(['card']);
\Phlex\Ui\View::addTo($app, ['ui' => 'ui clearing divider']);

\Phlex\Ui\Header::addTo($app, ['Models', 'size' => 1, 'subHeader' => 'Card may display information from many models.']);

$stat = new Stat($app->db);
$stat = $stat->loadAny();

$c = \Phlex\Ui\Card::addTo($app);
$c->setModel($stat, [$stat->key()->client_name, $stat->key()->description]);

$c->addSection('Project: ', $stat, [$stat->key()->start_date, $stat->key()->finish_date], true);

$country = $stat->client_country_iso;
$notify = $country->addUserAction('Notify', [
    'args' => [
        'note' => ['type' => 'string', 'required' => true],
    ],
    'callback' => function ($model, $note) {
        return 'Note to client is sent: ' . $note;
    },
]);
$c->addSection('Client Country:', $country, [$country->key()->iso, $country->key()->numcode, $country->key()->phonecode], true);

$c->addClickAction($notify, new Button(['Send Note']), [$country->id]);
