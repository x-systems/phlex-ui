<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

/** @var \Phlex\Ui\Webpage $app */
require_once __DIR__ . '/../init-app.php';

\Phlex\Ui\Button::addTo($app, ['Dynamic scroll in Lister', 'small left floated basic blue', 'icon' => 'left arrow'])
    ->link(['scroll-lister']);
\Phlex\Ui\Button::addTo($app, ['Dynamic scroll in Container', 'small right floated basic blue', 'iconRight' => 'right arrow'])
    ->link(['scroll-container']);
\Phlex\Ui\View::addTo($app, ['ui' => 'ui clearing divider']);

\Phlex\Ui\Header::addTo($app, ['Dynamic scroll in Table']);

$table = \Phlex\Ui\Table::addTo($app);

$model = $table->setModel(new Country($app->db));
//$model->addCondition(Country::hint()->key()->name, 'like', 'A%');

$table->addJsPaginator(30);
