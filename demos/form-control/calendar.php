<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\Form;
use Phlex\Ui\GridLayout;
use Phlex\Ui\JsToast;
use Phlex\Ui\Webpage;

/** @var Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

$layout = GridLayout::addTo($webpage, ['rows' => 1, 'columns' => 2]);

$form = Form::addTo($layout, [], ['r1c1']);

$form->addControl('date', [Form\Control\Calendar::class, 'type' => 'date'])
    ->set(new \DateTime());

$form->addControl('time', [Form\Control\Calendar::class, 'type' => 'time'])
    ->set(new \DateTime());

$form->addControl('datetime', [Form\Control\Calendar::class, 'type' => 'datetime'])
    ->set(new \DateTime());

// $form->addControl('date_range', [
//     Form\Control\Calendar::class,
//     'type' => 'date',
//     'caption' => 'Range mode',
//     'options' => ['mode' => 'range'],
// // 	'defaultFieldType' => 'text'
// ])->set(date('Y-m-d') . ' to ' . date('Y-m-d', strtotime('+1 Week')));

// $form->addControl('date_multi', [
//     Form\Control\Calendar::class,
//     'type' => 'date',
//     'caption' => 'Multiple mode',
//     'options' => ['mode' => 'multiple'],
// // 	'defaultFieldType' => 'text'
// ])->set(date('Y-m-d') . ', ' . date('Y-m-d', strtotime('+1 Day')) . ', ' . date('Y-m-d', strtotime('+2 Day')));

// $control = $form->addControl('date_action', [
//     Form\Control\Calendar::class,
//     'type' => 'date',
//     'caption' => 'Javascript action',
//     'options' => ['clickOpens' => false],
// ])->set(date('Y-m-d'));
// $control->addAction(['Today', 'icon' => 'calendar day'])->on('click', $control->getJsInstance()->setDate(date('Y-m-d')));
// $control->addAction(['Select...', 'icon' => 'calendar'])->on('click', $control->getJsInstance()->open());
// $control->addAction(['Clear', 'icon' => 'times red'])->on('click', $control->getJsInstance()->clear());

$form->onSubmit(function ($f) {
    return new JsToast(Webpage::encodeJson($f->model->get()));
});
