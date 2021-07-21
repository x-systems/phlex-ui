<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\Form;

/** @var \Phlex\Ui\Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

\Phlex\Ui\Button::addTo($webpage, ['Accordion in Form', 'small right floated basic blue', 'iconRight' => 'right arrow'])
    ->link(['form-section-accordion']);
\Phlex\Ui\View::addTo($webpage, ['ui' => 'ui clearing divider']);

$model = new CountryLock($webpage->db);
$model = $model->loadAny();

// Prevent form from saving
$noSave = function (Form $form) {
    return new \Phlex\Ui\JsToast([
        'title' => 'POSTed field values',
        'message' => '<pre>' . $form->getApp()->encodeJson($form->model->get()) . '</pre>',
        'class' => 'success',
        'displayTime' => 5000,
    ]);
};

////////////////////////////////

$form = Form::addTo($webpage);
$form->setModel($model, false);

$sublayout = $form->layout->addSubLayout([Form\Layout\Section::class]);

\Phlex\Ui\Header::addTo($sublayout, ['Column Section in Form']);
$sublayout->setModel($model, [$model->key()->name]);

$colsLayout = $form->layout->addSubLayout([Form\Layout\Section\Columns::class]);

$c1 = $colsLayout->addColumn();
$c1->setModel($model, [$model->key()->iso, $model->key()->iso3]);

$c2 = $colsLayout->addColumn();
$c2->setModel($model, [$model->key()->numcode/*, $model->key()->phonecode*/]);

$form->addControl($model->key()->phonecode);

$form->onSubmit($noSave);

\Phlex\Ui\View::addTo($webpage, ['ui' => 'divider']);

////////////////////////////////

$form = Form::addTo($webpage);
$form->setModel($model, false);

$sublayout = $form->layout->addSubLayout([Form\Layout\Section::class]);

\Phlex\Ui\Header::addTo($sublayout, ['Accordion Section in Form']);
$sublayout->setModel($model, [$model->key()->name]);

$accordionLayout = $form->layout->addSubLayout([Form\Layout\Section\Accordion::class]);

$a1 = $accordionLayout->addSection('Section 1');
$a1->setModel($model, [$model->key()->iso, $model->key()->iso3]);

$a2 = $accordionLayout->addSection('Section 2');
$a2->setModel($model, [$model->key()->numcode, $model->key()->phonecode]);

$form->onSubmit($noSave);

\Phlex\Ui\View::addTo($webpage, ['ui' => 'divider']);

////////////////////////////////

$form = Form::addTo($webpage);
$form->setModel($model, false);

$sublayout = $form->layout->addSubLayout([Form\Layout\Section::class]);

\Phlex\Ui\Header::addTo($sublayout, ['Tabs in Form']);
$sublayout->setModel($model, [$model->key()->name]);

$tabsLayout = $form->layout->addSubLayout([Form\Layout\Section\Tabs::class]);

$tab1 = $tabsLayout->addTab('Tab 1');
$tab1->addGroup('In Group')->setModel($model, [$model->key()->iso, $model->key()->iso3]);

$tab2 = $tabsLayout->addTab('Tab 2');
$tab2->setModel($model, [$model->key()->numcode, $model->key()->phonecode]);

$form->onSubmit($noSave);

\Phlex\Ui\View::addTo($webpage, ['ui' => 'divider']);

/////////////////////////////////////////

\Phlex\Ui\Header::addTo($webpage, ['Color in form']);

$form = Form::addTo($webpage);
$form->setModel($model, false);

$sublayout = $form->layout->addSubLayout([Form\Layout\Section::class, 'ui' => 'segment red inverted'], false);

\Phlex\Ui\Header::addTo($sublayout, ['This section in Red', 'ui' => 'dividing header', 'element' => 'h2']);
$sublayout->setModel($model, [$model->key()->name]);

$sublayout = $form->layout->addSubLayout([Form\Layout\Section::class, 'ui' => 'segment teal inverted']);
$colsLayout = $sublayout->addSubLayout([Form\Layout\Section\Columns::class]);

$c1 = $colsLayout->addColumn();
$c1->setModel($model, [$model->key()->iso, $model->key()->iso3]);

$c2 = $colsLayout->addColumn();
$c2->setModel($model, [$model->key()->numcode, $model->key()->phonecode]);

$form->onSubmit($noSave);

\Phlex\Ui\View::addTo($webpage, ['ui' => 'divider']);
