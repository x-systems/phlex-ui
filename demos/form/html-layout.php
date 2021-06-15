<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\Form;
use Phlex\Ui\GridLayout;
use Phlex\Ui\Header;
use Phlex\Ui\Tabs;
use Phlex\Ui\View;

/** @var \Phlex\Ui\App $app */
require_once __DIR__ . '/../init-app.php';

Header::addTo($app, ['Display form using Html template', 'subHeader' => 'Fully control how to display fields.']);

$tabs = Tabs::addTo($app);

$tab = $tabs->addTab('Layout using field name');

$form = FlyersForm::addTo($tab, [
    'layout' => [Form\Layout::class, ['defaultTemplate' => __DIR__ . '/templates/flyers-form-layout.html']],
]);

////////////////////////////////////////
$tab = $tabs->addTab('Template samples');

$gridLayout = GridLayout::addTo($tab, ['rows' => 1, 'columns' => 2])->addClass('internally celled');

$right = View::addTo($gridLayout, [], ['r1c1']);
Header::addTo($right, ['Button on right']);

$form = Form::addTo($right, ['layout' => [Form\Layout::class, 'defaultTemplate' => __DIR__ . '/templates/form-button-right.html']]);
$form->setModel((new Flyers(new \Phlex\Data\Persistence\Array_()))->tryLoadAny());
$form->getControl('last_name')->hint = 'Please enter your last name.';

$left = View::addTo($gridLayout, [], ['r1c2']);
Header::addTo($left, ['Hint placement']);

$form = Form::addTo($left, [
    'layout' => [
        Form\Layout::class,
        [
            'defaultInputTemplate' => __DIR__ . '/templates/input.html',
            'defaultHint' => [\Phlex\Ui\Label::class, 'class' => ['pointing', 'below']],
        ],
    ],
]);
$form->setModel((new Flyers(new \Phlex\Data\Persistence\Array_()))->tryLoadAny());
$form->getControl('last_name')->hint = 'Please enter your last name.';

////////////////////////////////////////
$tab = $tabs->addTab('Custom layout class');

$form = Form::addTo($tab, ['layout' => [Form\Layout\Custom::class, 'defaultTemplate' => __DIR__ . '/templates/form-custom-layout.html']]);
$form->setModel((new \Phlex\Ui\Demos\CountryLock($app->db))->loadAny());

$form->onSubmit(function ($form) {
    return new \Phlex\Ui\JsToast('Saving is disabled');
});
