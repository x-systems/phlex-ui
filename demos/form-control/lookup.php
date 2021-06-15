<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\Form;

/** @var \Phlex\Ui\App $app */
require_once __DIR__ . '/../init-app.php';

// create header
\Phlex\Ui\Header::addTo($app, ['Lookup Input']);

Form\Control\Lookup::addTo($app, ['placeholder' => 'Search country', 'label' => 'Country: '])->setModel(new Country($app->db));

// create form
$form = Form::addTo($app, ['segment']);
\Phlex\Ui\Label::addTo($form, ['Lookup countries', 'top attached'], ['AboveControls']);

$model = new \Phlex\Data\Model($app->db, ['table' => 'test']);

// Without Lookup
$model->hasOne('country1', ['model' => [Country::class]]);

// With Lookup
$model->hasOne('country2', ['model' => [Country::class], 'ui' => ['form' => [
    DemoLookup::class,
    'plus' => true,
]]]);

$form->setModel($model->createEntity());

$form->addControl('country3', [
    Form\Control\Lookup::class,
    'model' => new Country($app->db),
    'placeholder' => 'Search for country by name or iso value',
    'search' => ['name', 'iso', 'iso3'],
]);

$form->onSubmit(function (Form $form) {
    $str = $form->model->ref('country1')->get('name') . ' ' . $form->model->ref('country2')->get('name') . ' ' . (new Country($form->getApp()->db))->tryLoad($form->model->get('country3'))->get('name');
    $view = new \Phlex\Ui\Message('Select:'); // need in behat test.
    $view->invokeInit();
    $view->text->addParagraph($str);

    return $view;
});

\Phlex\Ui\Header::addTo($app, ['Lookup input using label']);

// from seed
Form\Control\Lookup::addTo($app, ['placeholder' => 'Search country', 'label' => 'Country: '])->setModel(new Country($app->db));

// through constructor
Form\Control\Lookup::addTo($app, ['placeholder' => 'Weight', 'labelRight' => new \Phlex\Ui\Label(['kg', 'basic'])]);
Form\Control\Lookup::addTo($app, ['label' => '$', 'labelRight' => new \Phlex\Ui\Label(['.00', 'basic'])]);

Form\Control\Lookup::addTo($app, [
    'iconLeft' => 'tags',
    'labelRight' => new \Phlex\Ui\Label(['Add Tag', 'tag']),
]);

// left/right corner is not supported, but here is work-around:
$label = new \Phlex\Ui\Label();
$label->addClass('left corner');
\Phlex\Ui\Icon::addTo($label, ['asterisk']);

Form\Control\Lookup::addTo($app, [
    'label' => $label,
])->addClass('left corner');

\Phlex\Ui\Header::addTo($app, ['Lookup input inside modal']);

$modal = \Phlex\Ui\Modal::addTo($app)->set(function ($p) {
    $a = Form\Control\Lookup::addTo($p, ['placeholder' => 'Search country', 'label' => 'Country: ']);
    $a->setModel(new Country($p->getApp()->db));
});
\Phlex\Ui\Button::addTo($app, ['Open Lookup on a Modal window'])->on('click', $modal->show());
