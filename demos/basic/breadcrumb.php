<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\Breadcrumb;
use Phlex\Ui\Form;
use Phlex\Ui\JsToast;
use Phlex\Ui\Table;
use Phlex\Ui\Table\Column\Link;
use Phlex\Ui\View;
use Phlex\Ui\Webpage;

/** @var Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

/**
 * Demonstrates how to use Breadcrumb.
 */
$crumb = Breadcrumb::addTo($webpage);
$crumb->addCrumb('UI Demo', ['index']);
$crumb->addCrumb('Breadcrumb Demo', ['breadcrumb']);

View::addTo($webpage, ['ui' => 'divider']);

$crumb->addCrumb('Countries', []);

$model = new CountryLock($webpage->db);
$model->setLimit(15);

if ($id = $webpage->stickyGet('country_id')) {
    // perhaps we edit individual country?
    $model = $model->load($id);
    $crumb->addCrumb($model->name, []);

    // here we can check for additional criteria and display a deeper level on the crumb

    $form = Form::addTo($webpage);
    $form->setModel($model);
    $form->onSubmit(function (Form $form) {
        return new JsToast('Form Submitted! Data saving is not possible in demo!');
    });
} else {
    // display list of countries
    $table = Table::addTo($webpage);
    $table->setModel($model);
    $table->addDecorator($model->key()->name, [Link::class, [], ['country_id' => 'id']]);
}

$crumb->popTitle();
