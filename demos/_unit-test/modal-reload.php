<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\Button;
use Phlex\Ui\Header;
use Phlex\Ui\Modal;

/** @var \Phlex\Ui\Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

// Simulating ModalExecutor reload for Behat test.

Header::addTo($webpage, ['Testing ModalExecutor reload']);

$modal = Modal::addTo($webpage->html, ['title' => 'Modal Executor', 'region' => 'Modals']);

$modal->set(function ($modal) {
    ReloadTest::addTo($modal);
});

$button = Button::addTo($webpage)->set('Test');
$button->on('click', $modal->show());
