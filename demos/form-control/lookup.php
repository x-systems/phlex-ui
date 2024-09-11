<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Data\Model;
use Phlex\Ui\Button;
use Phlex\Ui\Form;
use Phlex\Ui\Header;
use Phlex\Ui\Icon;
use Phlex\Ui\Label;
use Phlex\Ui\Message;
use Phlex\Ui\Modal;
use Phlex\Ui\Webpage;

/** @var Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

// create header
Header::addTo($webpage, ['Lookup Input']);

Form\Control\Lookup::addTo($webpage, ['placeholder' => 'Search country', 'label' => 'Country: '])->setModel(new Country($webpage->db));

// create form
$form = Form::addTo($webpage, ['segment']);
Label::addTo($form, ['Lookup countries', 'top attached'], ['AboveControls']);

$model = new Model($webpage->db, ['table' => 'test']);

// Without Lookup
$model->hasOne('country1', ['theirModel' => [Country::class]]);

// With Lookup
$model->hasOne('country2', ['theirModel' => [Country::class], 'options' => [
    Form\Control::OPTION_SEED => [
        DemoLookup::class,
        'plus' => true,
    ],
]]);

// foreach ($model->getFields() as $key => $field) {
//     print_r([$key, get_class($field), $field->isEditable()]);
//     ob_flush();
// }

$form->setModel($model);

$form->addControl('country3', [
    Form\Control\Lookup::class,
    'model' => new Country($webpage->db),
    'placeholder' => 'Search for country by name or iso value',
    'search' => ['name', 'iso', 'iso3'],
]);

$form->onSubmit(function (Form $form) {
    $str = $form->model->getTheirEntity('country1')->get('name') . ' ' . $form->model->getTheirEntity('country2')->get('name') . ' ' . (new Country($form->getApp()->db))->tryLoad($form->model->get('country3'))->get('name');
    $view = new Message('Select:'); // need in behat test.
    $view->initialize();
    $view->text->addParagraph($str);

    return $view;
});

Header::addTo($webpage, ['Lookup input using label']);

// from seed
Form\Control\Lookup::addTo($webpage, ['placeholder' => 'Search country', 'label' => 'Country: '])->setModel(new Country($webpage->db));

// through constructor
Form\Control\Lookup::addTo($webpage, ['placeholder' => 'Weight', 'labelRight' => new Label(['kg', 'basic'])]);
Form\Control\Lookup::addTo($webpage, ['label' => '$', 'labelRight' => new Label(['.00', 'basic'])]);

Form\Control\Lookup::addTo($webpage, [
    'iconLeft' => 'tags',
    'labelRight' => new Label(['Add Tag', 'tag']),
]);

// left/right corner is not supported, but here is work-around:
$label = new Label();
$label->addClass('left corner');
Icon::addTo($label, ['asterisk']);

Form\Control\Lookup::addTo($webpage, [
    'label' => $label,
])->addClass('left corner');

Header::addTo($webpage, ['Lookup input inside modal']);

$modal = Modal::addTo($webpage)->set(function ($p) {
    $a = Form\Control\Lookup::addTo($p, ['placeholder' => 'Search country', 'label' => 'Country: ']);
    $a->setModel(new Country($p->getApp()->db));
});
Button::addTo($webpage, ['Open Lookup on a Modal window'])->on('click', $modal->show());
