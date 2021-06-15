<?php

declare(strict_types=1);
/**
 * Behat testing.
 * Test for callback in callback.
 */

namespace Phlex\Ui\Demos;

use Phlex\Ui\Button;
use Phlex\Ui\Crud;
use Phlex\Ui\Header;
use Phlex\Ui\Loader;
use Phlex\Ui\UserAction\ExecutorFactory;

/** @var \Phlex\Ui\App $app */
require_once __DIR__ . '/../init-app.php';

$m = (new CountryLock($app->db))->setLimit(5);
$app->getExecutorFactory()->registerTrigger(
    ExecutorFactory::TABLE_BUTTON,
    [Button::class, 'ui' => 'atk-test button', 'icon' => 'pencil'],
    $m->getUserAction('edit')
);

$loader = Loader::addTo($app);
$loader->loadEvent = false;

$loader->set(function ($p) use ($m) {
    $loader_1 = Loader::addTo($p);
    $loader_1->loadEvent = false;

    Header::addTo($p, ['Loader-1', 'size' => 4]);

    $loader_1->set(function ($p) use ($m) {
        Header::addTo($p, ['Loader-2', 'size' => 4]);
        $loader_3 = Loader::addTo($p);

        $loader_3->set(function ($p) use ($m) {
            Header::addTo($p, ['Loader-3', 'size' => 4]);

            $c = Crud::addTo($p, ['ipp' => 4]);
            $c->setModel($m, [$m->fieldName()->name]);
        });
    });
    \Phlex\Ui\Button::addTo($p, ['Load2'])->js('click', $loader_1->jsLoad());
});

\Phlex\Ui\Button::addTo($app, ['Load1'])->js('click', $loader->jsLoad());
