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
use Phlex\Ui\Webpage;

/** @var Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

$vp = VirtualPage::addTo($webpage);

$vp->set(static function ($firstPage) {
    $secondVp = VirtualPage::addTo($firstPage);
    $secondVp->set(static function ($secondPage) {
        View::addTo($secondPage)->set('Second Level Page')->addClass('__phlex-behat-test-second');
        $thirdVp = VirtualPage::addTo($secondPage);
        $thirdVp->set(static function ($thirdPage) {
            View::addTo($thirdPage)->set('Third Level Page')->addClass('__phlex-behat-test-third');
            $form = Form::addTo($thirdPage);
            $form->addControl('category', [Form\Control\Lookup::class, 'model' => new Category($thirdPage->getApp()->db)]);
            $form->onSubmit(static function ($f) {
                $category = $f->getControl('category')->model->load($f->model->get('category'));

                return new JsToast($category->getTitle());
            });
        });
        Button::addTo($secondPage, ['Open Third'])->link($thirdVp->getUrl());
    });
    View::addTo($firstPage)->set('First Level Page')->addClass('__phlex-behat-test-first');
    Button::addTo($firstPage, ['Open Second'])->link($secondVp->getUrl());
});

Button::addTo($webpage, ['Open First'])->link($vp->getUrl());
