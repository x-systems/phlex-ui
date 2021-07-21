<?php

declare(strict_types=1);
/**
 * Behat testing.
 * Test for triggerOnReload = false for Callback.
 */

namespace Phlex\Ui\Demos;

use Phlex\Ui\Button;
use Phlex\Ui\Form;
use Phlex\Ui\Jquery;
use Phlex\Ui\JsToast;

/** @var \Phlex\Ui\Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

$m = (new CountryLock($webpage->db))->setLimit(5);

$vp = $webpage->addView(new \Phlex\Ui\VirtualPage());
$vp->cb->triggerOnReload = false;

$form = Form::addTo($vp);
$form->setModel($m->tryLoadAny(), [$m->key()->name]);
$form->getControl($m->key()->name)->caption = 'TestName';

$table = $webpage->addView(new \Phlex\Ui\Table());
$table->setModel($m);

$button = Button::addTo($webpage, ['First', ['ui' => 'atk-test']]);
$button->on('click', new \Phlex\Ui\JsModal('Edit First Record', $vp));

$form->onSubmit(function ($form) use ($table) {
    $form->model->save();

    return [
        $table->jsReload(),
        new JsToast('Save'),
        (new Jquery('.ui.modal.visible.active.front'))->modal('hide'),
    ];
});
