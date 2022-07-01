<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

/** @var \Phlex\Ui\Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

\Phlex\Ui\Button::addTo($webpage, ['Dynamic scroll in Lister', 'small left floated basic blue', 'icon' => 'left arrow'])
    ->link(['scroll-lister']);
\Phlex\Ui\Button::addTo($webpage, ['Dynamic scroll in Container', 'small right floated basic blue', 'iconRight' => 'right arrow'])
    ->link(['scroll-container']);
\Phlex\Ui\View::addTo($webpage, ['ui' => 'ui clearing divider']);

\Phlex\Ui\Header::addTo($webpage, ['Dynamic scroll in Table']);

$table = \Phlex\Ui\Table::addTo($webpage);

$model = $table->setModel(new Country($webpage->db));
// $model->addCondition(Country::hint()->key()->name, 'like', 'A%');

$table->addJsPaginator(30);
