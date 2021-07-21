<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\Form;
use Phlex\Ui\JsToast;

/** @var \Phlex\Ui\Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

$items = [
    [
        'name' => 'Electronics',
        'nodes' => [
            [
                'name' => 'Phone',
                'nodes' => [
                    [
                        'name' => 'iPhone',
                        'id' => 502,
                    ],
                    [
                        'name' => 'Google Pixels',
                        'id' => 503,
                    ],
                ],
            ],
            ['name' => 'Tv', 'id' => 501, 'nodes' => []],
            ['name' => 'Radio', 'id' => 601, 'nodes' => []],
        ],
    ],
    ['name' => 'Cleaner', 'id' => 201, 'nodes' => []],
    ['name' => 'Appliances', 'id' => 301, 'nodes' => []],
];

\Phlex\Ui\Header::addTo($webpage, ['Tree item selector']);

$form = Form::addTo($webpage);
$control = $form->addControl('tree', [Form\Control\TreeItemSelector::class, 'treeItems' => $items, 'caption' => 'Multiple selection:'], ['type' => 'array', 'serialize' => 'json']);
$control->set($webpage->encodeJson([201, 301, 503]));

//$control->onItem(function($value) use ($webpage) {
//    return new \Phlex\Ui\JsToast($webpage->encodeJson($value));
//});

$control = $form->addControl('tree1', [Form\Control\TreeItemSelector::class, 'treeItems' => $items, 'allowMultiple' => false, 'caption' => 'Single selection:']);
$control->set(502);

//$control->onItem(function($tree) {
//    return new JsToast('Received 1');
//});

$form->onSubmit(function (Form $form) use ($webpage) {
    $response = [
        'multiple' => $form->model->get('tree'),
        'single' => $form->model->get('tree1'),
    ];

    $view = new \Phlex\Ui\Message('Items: ');
    $view->initialize();
    $view->text->addParagraph($webpage->encodeJson($response));

    return $view;
});
