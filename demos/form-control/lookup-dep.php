<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\Form;

/** @var \Phlex\Ui\Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

\Phlex\Ui\Header::addTo($webpage, ['Lookup dependency']);

$form = Form::addTo($webpage, ['segment']);
\Phlex\Ui\Label::addTo($form, ['Input information here', 'top attached'], ['AboveControls']);

$form->addControl('starts_with', [
    Form\Control\Dropdown::class,
    'values' => [
        'a' => 'Letter A',
        'b' => 'Letter B',
        'c' => 'Letter C',
    ],
    'isMultiple' => true,
    'hint' => 'Select start letter that lookup selection of Country will depend on.',
    'placeholder' => 'Search for country starting with ...',
]);

$form->addControl('contains', [
    Form\Control\Line::class,
    'hint' => 'Select string that lookup selection of Country will depend on.',
    'placeholder' => 'Search for country containing ...',
]);

$lookup = $form->addControl('country', [
    Form\Control\Lookup::class,
    'model' => new Country($webpage->db),
    'dependency' => function (Country $model, $data) {
        foreach (explode(',', $data['starts_with'] ?? '') as $letter) {
            $model->addCondition($model->key()->name, 'like', $letter . '%');
        }

        isset($data['contains']) ? $model->addCondition($model->key()->name, 'like', '%' . $data['contains'] . '%') : null;
    },
    'placeholder' => 'Selection depends on Dropdown above',
    'search' => [Country::hint()->key()->name, Country::hint()->key()->iso, Country::hint()->key()->iso3],
]);

$form->onSubmit(function (Form $form) {
    return 'Submitted: ' . print_r($form->model->get(), true);
});

\Phlex\Ui\Header::addTo($webpage, ['Lookup multiple values']);

$form = Form::addTo($webpage, ['segment']);
\Phlex\Ui\Label::addTo($form, ['Input information here', 'top attached'], ['AboveControls']);

$form->addControl('ends_with', [
    Form\Control\Dropdown::class,
    'values' => [
        'a' => 'Letter A',
        'e' => 'Letter E',
        'y' => 'Letter Y',
    ],
    'hint' => 'Select end letter that lookup selection of Country will depend on.',
    'placeholder' => 'Search for country ending with ...',
]);

$lookup = $form->addControl('country', [
    Form\Control\Lookup::class,
    'model' => new Country($webpage->db),
    'dependency' => function (Country $model, $data) {
        isset($data['ends_with']) ? $model->addCondition($model->key()->name, 'like', '%' . $data['ends_with']) : null;
    },
    'multiple' => true,
    'search' => [Country::hint()->key()->name, Country::hint()->key()->iso, Country::hint()->key()->iso3],
]);

$form->onSubmit(function (Form $form) {
    return 'Submitted: ' . print_r($form->model->get(), true);
});
