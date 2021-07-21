<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

/** @var \Phlex\Ui\Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

\Phlex\Ui\Header::addTo($webpage, ['Card displays read-only data of a single record']);

\Phlex\Ui\CardTable::addTo($webpage)->setModel((new Stat($webpage->db))->tryLoadAny());
