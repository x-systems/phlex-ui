<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

/** @var \Phlex\Ui\Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

$layout = \Phlex\Ui\Layout\Admin::addTo($webpage);

$menu = $layout->menu->addMenu(['Layouts', 'icon' => 'puzzle']);
$menu->addItem(\Phlex\Ui\Layout\Centered::class);
$menu->addItem(\Phlex\Ui\Layout\Admin::class);

$menuRight = $layout->menuRight;
$menuRight->addItem(['Warning', 'red', 'icon' => 'red warning']);
$menuUser = $menuRight->addMenu('John Smith');
$menuUser->addItem('Profile');
$menuUser->addDivider();
$menuUser->addItem('Logout');

$menu = $layout->menu->addMenu(['Component Demo', 'icon' => 'puzzle']);
$menuForm = $menu->addMenu('Forms');
$menuForm->addItem('Form Controls');
$menuForm->addItem('Form Layouts');
$menu->addItem('Crud');

$layout->menuLeft->addItem(['Home', 'icon' => 'home']);
$layout->menuLeft->addItem(['Topics', 'icon' => 'block layout']);
$layout->menuLeft->addItem(['Friends', 'icon' => 'smile']);
$layout->menuLeft->addItem(['History', 'icon' => 'calendar']);
$layout->menuLeft->addItem(['Settings', 'icon' => 'cogs']);

$layout->template->set('Footer', 'Phlex UI is awesome');

\Phlex\Ui\Header::addTo($layout, ['Basic Form Example']);

$form = \Phlex\Ui\Form::addTo($layout, ['segment']);
$form->setModel(new \Phlex\Data\Model());

$formGroup = $form->addGroup('Name');
$formGroup->addControl('first_name', ['width' => 'eight']);
$formGroup->addControl('middle_name', ['width' => 'three']);
$formGroup->addControl('last_name', ['width' => 'five']);

$formGroup = $form->addGroup('Address');
$formGroup->addControl('address', ['width' => 'twelve']);
$formGroup->addControl('zip', ['width' => 'four']);

$form->onSubmit(function (\Phlex\Ui\Form $form) {
    $errors = [];

    foreach (['first_name', 'last_name', 'address'] as $field) {
        if (!$form->model->get($field)) {
            $errors[] = $form->error($field, 'Field ' . $field . ' is mandatory');
        }
    }

    return $errors ?: $form->success('No more errors', 'so we have saved everything into the database');
});
