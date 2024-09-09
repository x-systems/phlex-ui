<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\Button;
use Phlex\Ui\Header;
use Phlex\Ui\Table;
use Phlex\Ui\View;
use Phlex\Ui\Webpage;

/** @var Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

Button::addTo($webpage, ['Dynamic scroll in Lister', 'small left floated basic blue', 'icon' => 'left arrow'])
    ->link(['scroll-lister']);
Button::addTo($webpage, ['Dynamic scroll in Container', 'small right floated basic blue', 'iconRight' => 'right arrow'])
    ->link(['scroll-container']);
View::addTo($webpage, ['ui' => 'ui clearing divider']);

Header::addTo($webpage, ['Dynamic scroll in Table']);

$table = Table::addTo($webpage);

$model = $table->setModel(new Country($webpage->db));
// $model->addCondition(Country::hint()->key()->name, 'like', 'A%');

$table->addJsPaginator(30);
