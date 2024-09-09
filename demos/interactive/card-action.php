<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\Button;
use Phlex\Ui\Card;
use Phlex\Ui\Header;
use Phlex\Ui\View;
use Phlex\Ui\Webpage;

/** @var Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

Button::addTo($webpage, ['Card', 'small left floated basic blue', 'icon' => 'left arrow'])
    ->link(['card']);
View::addTo($webpage, ['ui' => 'ui clearing divider']);

Header::addTo($webpage, ['Models', 'size' => 1, 'subHeader' => 'Card may display information from many models.']);

$stat = new Stat($webpage->db);
$stat = $stat->loadAny();

$c = Card::addTo($webpage);
$c->setModel($stat, [$stat->key()->client_name, $stat->key()->description]);

$c->addSection('Project: ', $stat, [$stat->key()->start_date, $stat->key()->finish_date], true);

$country = $stat->client_country_iso;
$notify = $country->addUserAction('Notify', [
    'args' => [
        'note' => ['type' => 'string', 'required' => true],
    ],
    'callback' => static function ($model, $note) {
        return 'Note to client is sent: ' . $note;
    },
]);
$c->addSection('Client Country:', $country, [$country->key()->iso, $country->key()->numcode, $country->key()->phonecode], true);

$c->addClickAction($notify, new Button(['Send Note']), [$country->id]);
