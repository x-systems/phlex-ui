<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

/** @var \Phlex\Ui\App $app */
require_once __DIR__ . '/../init-app.php';

\Phlex\Ui\Header::addTo($app, ['Card displays read-only data of a single record']);

\Phlex\Ui\CardTable::addTo($app)->setModel((new Stat($app->db))->tryLoadAny());
