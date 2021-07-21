<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Data\Model;
use Phlex\Ui\Form;
use Phlex\Ui\JsReload;

/** @var \Phlex\Ui\Webpage $app */
require_once __DIR__ . '/../init-app.php';

// Testing form.

\Phlex\Ui\Header::addTo($app, ['Form automatically decided how many columns to use']);

$buttons = \Phlex\Ui\View::addTo($app, ['ui' => 'green basic buttons']);

$seg = \Phlex\Ui\View::addTo($app, ['ui' => 'raised segment']);

\Phlex\Ui\Button::addTo($buttons, ['Use Country Model', 'icon' => 'arrow down'])
    ->on('click', new JsReload($seg, ['m' => 'country']));
\Phlex\Ui\Button::addTo($buttons, ['Use File Model', 'icon' => 'arrow down'])
    ->on('click', new JsReload($seg, ['m' => 'file']));
\Phlex\Ui\Button::addTo($buttons, ['Use Stat Model', 'icon' => 'arrow down'])
    ->on('click', new JsReload($seg, ['m' => 'stat']));

$form = Form::addTo($seg, ['layout' => [Form\Layout\Columns::class]]);
$form->setModel((
    isset($_GET['m']) ? (
        $_GET['m'] === 'country' ? new Country($app->db) : (
            $_GET['m'] === 'file' ? new File($app->db) : new Stat($app->db)
        )
    ) : new Stat($app->db)
)->tryLoadAny());

$form->onSubmit(function (Form $form) {
    $errors = [];
    $modelDirty = \Closure::bind(function () use ($form): array { // TODO Model::dirty property is private
        return $form->model->dirty;
    }, null, Model::class)();
    foreach ($modelDirty as $field => $value) {
        // we should care only about editable fields
        if ($form->model->getField($field)->isEditable()) {
            $errors[] = $form->error($field, 'Value was changed, ' . $form->getApp()->encodeJson($value) . ' to ' . $form->getApp()->encodeJson($form->model->get($field)));
        }
    }

    return $errors ?: 'No fields were changed';
});
