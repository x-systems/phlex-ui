<?php

declare(strict_types=1);
/**
 * Test for VirtualPage inside VirtualPage.
 */

namespace Phlex\Ui\Demos;

use Phlex\Ui\Button;
use Phlex\Ui\Form;
use Phlex\Ui\JsToast;
use Phlex\Ui\View;
use Phlex\Ui\VirtualPage;

/** @var \Phlex\Ui\App $app */
require_once __DIR__ . '/../init-app.php';

$vp = VirtualPage::addTo($app);

$vp->set(function ($firstPage) {
    $secondVp = VirtualPage::addTo($firstPage);
    $secondVp->set(function ($secondPage) {
        View::addTo($secondPage)->set('Second Level Page')->addClass('__atk-behat-test-second');
        $thirdVp = VirtualPage::addTo($secondPage);
        $thirdVp->set(function ($thirdPage) {
            View::addTo($thirdPage)->set('Third Level Page')->addClass('__atk-behat-test-third');
            $form = Form::addTo($thirdPage);
            $form->addControl('category', [Form\Control\Lookup::class, 'model' => new Category($thirdPage->getApp()->db)]);
            $form->onSubmit(function ($f) {
                $category = $f->getControl('category')->model->load($f->model->get('category'));

                return new JsToast($category->getTitle());
            });
        });
        Button::addTo($secondPage, ['Open Third'])->link($thirdVp->getUrl());
    });
    View::addTo($firstPage)->set('First Level Page')->addClass('__atk-behat-test-first');
    Button::addTo($firstPage, ['Open Second'])->link($secondVp->getUrl());
});

Button::addTo($app, ['Open First'])->link($vp->getUrl());
