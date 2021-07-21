<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

/** @var \Phlex\Ui\Webpage $app */
require_once __DIR__ . '/../init-app.php';

\Phlex\Ui\Button::addTo($app, ['Notify Examples - Page 2', 'small right floated basic blue', 'iconRight' => 'right arrow'])
    ->link(['notify2']);

\Phlex\Ui\Button::addTo($app, ['Test'])->on('click', (new \Phlex\Ui\JsNotify('Not yet implemented'))->setColor('red'));

$modal = \Phlex\Ui\Modal::addTo($app, ['Modal Title']);

$modal->set(function ($p) use ($modal) {
    $form = \Phlex\Ui\Form::addTo($p);
    $form->addControl('name', null, ['caption' => 'Add your name']);

    $form->onSubmit(function (\Phlex\Ui\Form $form) use ($modal) {
        if (empty($form->model->get('name'))) {
            return $form->error('name', 'Please add a name!');
        }

        return [
            $modal->hide(),
            new \Phlex\Ui\JsNotify('Thank you ' . $form->model->get('name')),
        ];
    });
});

\Phlex\Ui\Button::addTo($app, ['Open Modal'])->on('click', $modal->show());
