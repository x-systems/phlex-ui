<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

/** @var \Phlex\Ui\App $app */
require_once __DIR__ . '/../init-app.php';

/** @var \Phlex\Data\Model $notifierClass */
$notifierClass = get_class(new class() extends \Phlex\Data\Model {
    public $table = 'notifier';

    protected function doInitialize(): void
    {
        parent::doInitialize();

        $this->addField('text', ['default' => 'This text will appear in notification', 'caption' => 'type any text']);
        $this->addField('icon', ['default' => 'warning sign', 'caption' => 'Use semantic-ui icon name']);
        $this->addField('color', ['type' => ['enum', 'values' => ['green', 'red', 'orange', 'yellow', 'teal', 'blue', 'violet', 'purple', 'pink', 'brown']], 'default' => 'green', 'caption' => 'Select color:']);
        $this->addField('transition', ['type' => ['enum', 'values' => ['scale', 'fade', 'jiggle', 'flash']], 'default' => 'jiggle', 'caption' => 'Select transition:']);
        $this->addField('width', ['type' => ['enum', 'values' => ['25%', '50%', '75%', '100%']], 'default' => '25%', 'caption' => 'Select width:']);
        $this->addField('position', ['type' => ['enum', 'values' => ['topLeft', 'topCenter', 'topRight', 'bottomLeft', 'bottomCenter', 'bottomRight', 'center']], 'default' => 'topRight', 'caption' => 'Select position:']);
        $this->addField('attach', ['type' => ['enum', 'values' => ['Body', 'Form']], 'default' => 'Body', 'caption' => 'Attach to:']);
    }
});

 // Notification type form
$head = \Phlex\Ui\Header::addTo($app, ['Notification Types']);

$form = \Phlex\Ui\Form::addTo($app, ['segment']);
// Unit test only.
$form->cb->setUrlTrigger('test_notify');

\Phlex\Ui\Label::addTo($form, ['Some of notification options that can be set.', 'top attached'], ['AboveControls']);
$form->buttonSave->set('Show');
$form->setModel((new $notifierClass($app->db))->createEntity(), false);

$formGroup = $form->addGroup(['Set Text and Icon:']);
$formGroup->addControl('text', ['width' => 'eight']);
$formGroup->addControl('icon', ['width' => 'four']);

$formGroup1 = $form->addGroup(['Set Color, Transition and Width:']);
$formGroup1->addControl('color', ['width' => 'four']);
$formGroup1->addControl('transition', ['width' => 'four']);
$formGroup1->addControl('width', ['width' => 'four']);

$formGroup2 = $form->addGroup(['Set Position and Attach to:']);
$formGroup2->addControl('position', ['width' => 'four']);
$formGroup2->addControl('attach', ['width' => 'four']);

$form->onSubmit(function (\Phlex\Ui\Form $form) {
    $notifier = new \Phlex\Ui\JsNotify();
    $notifier->setColor($form->model->get('color'))
        ->setPosition($form->model->get('position'))
        ->setWidth(rtrim($form->model->get('width'), '%'))
        ->setContent($form->model->get('text'))
        ->setTransition($form->model->get('transition'))
        ->setIcon($form->model->get('icon'));

    if ($form->model->get('attach') !== 'Body') {
        $notifier->attachTo($form);
    }

    return $notifier;
});
