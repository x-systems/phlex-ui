<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\Form;
use Phlex\Ui\View;

/** @var \Phlex\Ui\Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

$output = function (string $date) {
    $view = new \Phlex\Ui\Message();
    $view->initialize();
    $view->text->addHtml($date);

    return $view;
};

\Phlex\Ui\Header::addTo($webpage, ['Testing flatpickr using Behat']);
$form = Form::addTo($webpage);
$c = $form->addControl('field', null, ['type' => 'date']);
$form->buttonSave->set($c->elementId);

$form->onSubmit(function ($form) use ($output, $c, $webpage) {
    return $output($form->model->get($c->elementId)->format($webpage->ui_persistence->date_format));
});

View::addTo($webpage, ['ui' => 'hidden divider']);
$webpage->ui_persistence->date_format = 'Y-m-d';
$form = Form::addTo($webpage);
$c = $form->addControl('date_ymd', [Form\Control\Calendar::class, 'type' => 'date']);
$form->buttonSave->set($c->elementId);

$form->onSubmit(function ($form) use ($output, $c) {
    return $output($form->model->get($c->elementId));
});

View::addTo($webpage, ['ui' => 'hidden divider']);
$webpage->ui_persistence->time_format = 'H:i:s';
$form = Form::addTo($webpage);
$c = $form->addControl('time_24hr', [Form\Control\Calendar::class, 'type' => 'time']);
$form->buttonSave->set($c->elementId);

$form->onSubmit(function ($form) use ($output, $c) {
    return $output($form->model->get($c->elementId));
});

View::addTo($webpage, ['ui' => 'hidden divider']);
$webpage->ui_persistence->time_format = 'G:i A';
$form = Form::addTo($webpage);
$c = $form->addControl('time_am', [Form\Control\Calendar::class, 'type' => 'time']);
$form->buttonSave->set($c->elementId);

$form->onSubmit(function ($form) use ($output, $c) {
    return $output($form->model->get($c->elementId));
});

View::addTo($webpage, ['ui' => 'hidden divider']);
$webpage->ui_persistence->datetime_format = 'Y-m-d (H:i:s)';
$form = Form::addTo($webpage);
$c = $form->addControl('datetime', [Form\Control\Calendar::class, 'type' => 'datetime']);
$form->buttonSave->set($c->elementId);

$form->onSubmit(function ($form) use ($output, $c) {
    return $output($form->model->get($c->elementId));
});
