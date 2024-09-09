<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\Button;
use Phlex\Ui\Card;
use Phlex\Ui\Header;
use Phlex\Ui\Image;
use Phlex\Ui\View;
use Phlex\Ui\Webpage;

/** @var Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

Button::addTo($webpage, ['Card Model', 'small right floated basic blue', 'iconRight' => 'right arrow'])
    ->link(['card-action']);
View::addTo($webpage, ['ui' => 'ui clearing divider']);

Header::addTo($webpage, ['Card.', 'size' => 1, 'subHeader' => 'Component based on Fomantic-Ui Card view.']);

// *** Simple Card **/

Header::addTo($webpage, ['Card can be defined manually.', 'size' => 3]);

$card = Card::addTo($webpage);

$card->addContent(new Header(['Meet Kristy', 'subHeader' => 'Friends']));

$card->addDescription('Kristy is a friend of Mully.');
$card->addImage('../images/kristy.png');

$card->addButton(new Button(['Join']));
$card->addButton(new Button(['Email']));

$card->addExtraContent(new View(['Copyright notice: Image from Semantic-UI (Fomantic-UI)', 'element' => 'span']));

// *** Simple Card **/

$card = Card::addTo($webpage);
$content = new View(['class' => ['content']]);
$content->addView($img = new Image(['../images/kristy.png']));
$img->addClass('right floated mini ui image');
$content->addView($header = new Header(['Kristy']));

$card->addContent($content);
$card->addDescription('Friend of Bob');

// **** Card with Table and Label***/

Header::addTo($webpage, ['Card can display model label in a table or in line.', 'size' => 3]);

$deck = View::addTo($webpage, ['ui' => 'cards']);

$cardStat = Card::addTo($deck, ['useTable' => true]);
$cardStat->addContent(new Header(['Project Info']));
$stat = (new Stat($webpage->db))->tryLoadAny();

$cardStat->setModel($stat, [$stat->key()->project_name, $stat->key()->project_code, $stat->key()->client_name, $stat->key()->start_date]);

$btn = $cardStat->addButton(new Button(['Email Client']));

$cardStat = Card::addTo($deck, ['useLabel' => true]);
$cardStat->addContent(new Header(['Project Info']));
$stat = (new Stat($webpage->db))->tryLoadAny();

$cardStat->setModel($stat, [$stat->key()->project_name, $stat->key()->project_code, $stat->key()->client_name, $stat->key()->start_date]);

$cardStat->addButton(new Button(['Email Client']));

// **** Card display horizontally ***/

Header::addTo($webpage, ['Card can be display horizontally and/or centered.', 'size' => 3]);

$card = Card::addTo($webpage)->addClass('horizontal centered');

$card->addContent(new Header(['Meet Kristy', 'subHeader' => 'Friends']));
$card->addDescription('Kristy is a friend of Mully.');
$card->addImage('../images/kristy.png');
