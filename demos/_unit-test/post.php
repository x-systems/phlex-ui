<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\Form;
use Phlex\Ui\JsToast;
use Phlex\Ui\Webpage;

/** @var Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

$form = Form::addTo($webpage);
$form->cb->setUrlTrigger('test_submit');

$form->addControl('f1')->set('v1');

$form->onSubmit(static function ($form) {
    return new JsToast('Post ok');
});
