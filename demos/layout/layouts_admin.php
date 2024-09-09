<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Data\Model;
use Phlex\Ui\Form;
use Phlex\Ui\Header;
use Phlex\Ui\Layout\Admin;
use Phlex\Ui\Layout\Centered;
use Phlex\Ui\Webpage;

/** @var Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

$layout = Admin::addTo($webpage);

$menu = $layout->menu->addMenu(['Layouts', 'icon' => 'puzzle']);
$menu->addItem(Centered::class);
$menu->addItem(Admin::class);

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

Header::addTo($layout, ['Basic Form Example']);

$form = Form::addTo($layout, ['segment']);
$form->setModel(new Model());

$formGroup = $form->addGroup('Name');
$formGroup->addControl('first_name', ['width' => 'eight']);
$formGroup->addControl('middle_name', ['width' => 'three']);
$formGroup->addControl('last_name', ['width' => 'five']);

$formGroup = $form->addGroup('Address');
$formGroup->addControl('address', ['width' => 'twelve']);
$formGroup->addControl('zip', ['width' => 'four']);

$form->onSubmit(static function (Form $form) {
    $errors = [];

    foreach (['first_name', 'last_name', 'address'] as $field) {
        if (!$form->model->get($field)) {
            $errors[] = $form->error($field, 'Field ' . $field . ' is mandatory');
        }
    }

    return $errors ?: $form->success('No more errors', 'so we have saved everything into the database');
});
