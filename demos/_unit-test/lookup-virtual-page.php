<?php

declare(strict_types=1);
/**
 * Test for Lookup inside VirtualPage.
 */

namespace Phlex\Ui\Demos;

use Phlex\Ui\Form;
use Phlex\Ui\Grid;
use Phlex\Ui\JsModal;
use Phlex\Ui\JsToast;
use Phlex\Ui\VirtualPage;

/** @var \Phlex\Ui\Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

$product = new ProductLock($webpage->db);

$vp = VirtualPage::addTo($webpage);

$vp->set(function ($page) {
    $form = Form::addTo($page);
    $form->addControl('category', [Form\Control\Lookup::class, 'model' => new Category($page->getApp()->db)]);
    $form->onSubmit(function ($f) {
        $category = $f->getControl('category')->model->load($f->model->get('category'));

        return new JsToast($category->getTitle());
    });
});

$g = Grid::addTo($webpage, ['menu' => ['class' => ['atk-grid-menu']]]);
$g->setModel($product);

$g->menu->addItem(
    ['Add Category'],
    new JsModal('New Category', $vp)
);
