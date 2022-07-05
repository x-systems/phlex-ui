<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Data\Model;
use Phlex\Ui\Form;
use Phlex\Ui\JsReload;

/** @var \Phlex\Ui\Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

// Testing form.

\Phlex\Ui\Header::addTo($webpage, ['Form automatically decided how many columns to use']);

$buttons = \Phlex\Ui\View::addTo($webpage, ['ui' => 'green basic buttons']);

$seg = \Phlex\Ui\View::addTo($webpage, ['ui' => 'raised segment']);

\Phlex\Ui\Button::addTo($buttons, ['Use Country Model', 'icon' => 'arrow down'])
    ->on('click', new JsReload($seg, ['m' => 'country']));
\Phlex\Ui\Button::addTo($buttons, ['Use File Model', 'icon' => 'arrow down'])
    ->on('click', new JsReload($seg, ['m' => 'file']));
\Phlex\Ui\Button::addTo($buttons, ['Use Stat Model', 'icon' => 'arrow down'])
    ->on('click', new JsReload($seg, ['m' => 'stat']));

$form = Form::addTo($seg, ['layout' => [Form\Layout\Columns::class]]);
$form->setModel((
    isset($_GET['m']) ? (
        $_GET['m'] === 'country' ? new Country($webpage->db) : (
            $_GET['m'] === 'file' ? new File($webpage->db) : new Stat($webpage->db)
        )
    ) : new Stat($webpage->db)
)->tryLoadAny());

$form->onSubmit(function (Form $form) {
    $errors = [];
    $modelDirty = \Closure::bind(function () use ($form): array {
        return $form->model->getEntry()->getDirty();
    }, null, Model::class)();
    foreach ($modelDirty as $key => $value) {
        // we should care only about editable fields
        if ($form->model->getField($key)->isEditable()) {
        	$errors[] = $form->error($key, 'Value was changed, ' . $form->getApp()->encodeJson($form->model->getEntry()->getLoaded($key)) . ' to ' . $form->getApp()->encodeJson($value));
        }
    }

    return $errors ?: 'No fields were changed';
});
