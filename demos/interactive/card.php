<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

/** @var \Phlex\Ui\Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

\Phlex\Ui\Button::addTo($webpage, ['Card Model', 'small right floated basic blue', 'iconRight' => 'right arrow'])
    ->link(['card-action']);
\Phlex\Ui\View::addTo($webpage, ['ui' => 'ui clearing divider']);

\Phlex\Ui\Header::addTo($webpage, ['Card.', 'size' => 1, 'subHeader' => 'Component based on Fomantic-Ui Card view.']);

// *** Simple Card **/

\Phlex\Ui\Header::addTo($webpage, ['Card can be defined manually.', 'size' => 3]);

$card = \Phlex\Ui\Card::addTo($webpage);

$card->addContent((new \Phlex\Ui\Header(['Meet Kristy', 'subHeader' => 'Friends'])));

$card->addDescription('Kristy is a friend of Mully.');
$card->addImage('../images/kristy.png');

$card->addButton(new \Phlex\Ui\Button(['Join']));
$card->addButton(new \Phlex\Ui\Button(['Email']));

$card->addExtraContent(new \Phlex\Ui\View(['Copyright notice: Image from Semantic-UI (Fomantic-UI)', 'element' => 'span']));

// *** Simple Card **/

$card = \Phlex\Ui\Card::addTo($webpage);
$content = new \Phlex\Ui\View(['class' => ['content']]);
$content->addView($img = new \Phlex\Ui\Image(['../images/kristy.png']));
$img->addClass('right floated mini ui image');
$content->addView($header = new \Phlex\Ui\Header(['Kristy']));

$card->addContent($content);
$card->addDescription('Friend of Bob');

// **** Card with Table and Label***/

\Phlex\Ui\Header::addTo($webpage, ['Card can display model label in a table or in line.', 'size' => 3]);

$deck = \Phlex\Ui\View::addTo($webpage, ['ui' => 'cards']);

$cardStat = \Phlex\Ui\Card::addTo($deck, ['useTable' => true]);
$cardStat->addContent(new \Phlex\Ui\Header(['Project Info']));
$stat = (new Stat($webpage->db))->tryLoadAny();

$cardStat->setModel($stat, [$stat->key()->project_name, $stat->key()->project_code, $stat->key()->client_name, $stat->key()->start_date]);

$btn = $cardStat->addButton(new \Phlex\Ui\Button(['Email Client']));

$cardStat = \Phlex\Ui\Card::addTo($deck, ['useLabel' => true]);
$cardStat->addContent(new \Phlex\Ui\Header(['Project Info']));
$stat = (new Stat($webpage->db))->tryLoadAny();

$cardStat->setModel($stat, [$stat->key()->project_name, $stat->key()->project_code, $stat->key()->client_name, $stat->key()->start_date]);

$cardStat->addButton(new \Phlex\Ui\Button(['Email Client']));

// **** Card display horizontally ***/

\Phlex\Ui\Header::addTo($webpage, ['Card can be display horizontally and/or centered.', 'size' => 3]);

$card = \Phlex\Ui\Card::addTo($webpage)->addClass('horizontal centered');

$card->addContent((new \Phlex\Ui\Header(['Meet Kristy', 'subHeader' => 'Friends'])));
$card->addDescription('Kristy is a friend of Mully.');
$card->addImage('../images/kristy.png');
