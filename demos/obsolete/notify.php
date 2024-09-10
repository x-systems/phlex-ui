<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\Button;
use Phlex\Ui\Form;
use Phlex\Ui\JsNotify;
use Phlex\Ui\Modal;
use Phlex\Ui\Webpage;

/** @var Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

Button::addTo($webpage, ['Notify Examples - Page 2', 'small right floated basic blue', 'iconRight' => 'right arrow'])
    ->link(['notify2']);

Button::addTo($webpage, ['Test'])->on('click', (new JsNotify('Not yet implemented'))->setColor('red'));

$modal = Modal::addTo($webpage, ['Modal Title']);

$modal->set(function ($p) use ($modal) {
    $form = Form::addTo($p);
    $form->addControl('name', null, ['caption' => 'Add your name']);

    $form->onSubmit(function (Form $form) use ($modal) {
        if (empty($form->model->get('name'))) {
            return $form->error('name', 'Please add a name!');
        }

        return [
            $modal->hide(),
            new JsNotify('Thank you ' . $form->model->get('name')),
        ];
    });
});

Button::addTo($webpage, ['Open Modal'])->on('click', $modal->show());
