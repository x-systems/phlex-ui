<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\CardTable;
use Phlex\Ui\Header;
use Phlex\Ui\Webpage;

/** @var Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

Header::addTo($webpage, ['Card displays read-only data of a single record']);

CardTable::addTo($webpage)->setModel((new Stat($webpage->db))->tryLoadAny());
