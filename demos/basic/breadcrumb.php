<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

/** @var \Phlex\Ui\App $app */
require_once __DIR__ . '/../init-app.php';

/**
 * Demonstrates how to use Breadcrumb.
 */
$crumb = \Phlex\Ui\Breadcrumb::addTo($app);
$crumb->addCrumb('UI Demo', ['index']);
$crumb->addCrumb('Breadcrumb Demo', ['breadcrumb']);

\Phlex\Ui\View::addTo($app, ['ui' => 'divider']);

$crumb->addCrumb('Countries', []);

$model = new CountryLock($app->db);
$model->setLimit(15);

if ($id = $app->stickyGet('country_id')) {
    // perhaps we edit individual country?
    $model = $model->load($id);
    $crumb->addCrumb($model->name, []);

    // here we can check for additional criteria and display a deeper level on the crumb

    $form = \Phlex\Ui\Form::addTo($app);
    $form->setModel($model);
    $form->onSubmit(function (\Phlex\Ui\Form $form) {
        return new \Phlex\Ui\JsToast('Form Submitted! Data saving is not possible in demo!');
    });
} else {
    // display list of countries
    $table = \Phlex\Ui\Table::addTo($app);
    $table->setModel($model);
    $table->addDecorator($model->key()->name, [\Phlex\Ui\Table\Column\Link::class, [], ['country_id' => 'id']]);
}

$crumb->popTitle();
