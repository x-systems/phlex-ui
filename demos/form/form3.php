<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Data\Model;
use Phlex\Ui\Button;
use Phlex\Ui\Form;
use Phlex\Ui\Header;
use Phlex\Ui\JsReload;
use Phlex\Ui\View;
use Phlex\Ui\Webpage;

/** @var Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

// Testing form.

Header::addTo($webpage, ['Form automatically decided how many columns to use']);

$buttons = View::addTo($webpage, ['ui' => 'green basic buttons']);

$seg = View::addTo($webpage, ['ui' => 'raised segment']);

Button::addTo($buttons, ['Use Country Model', 'icon' => 'arrow down'])
    ->on('click', new JsReload($seg, ['m' => 'country']));
Button::addTo($buttons, ['Use File Model', 'icon' => 'arrow down'])
    ->on('click', new JsReload($seg, ['m' => 'file']));
Button::addTo($buttons, ['Use Stat Model', 'icon' => 'arrow down'])
    ->on('click', new JsReload($seg, ['m' => 'stat']));

$form = Form::addTo($seg, ['layout' => [Form\Layout\Columns::class]]);
$form->setModel((
    isset($_GET['m']) ? (
        $_GET['m'] === 'country' ? new Country($webpage->db) : (
            $_GET['m'] === 'file' ? new File($webpage->db) : new Stat($webpage->db)
        )
    ) : new Stat($webpage->db)
)->tryLoadAny());

$form->onSubmit(static function (Form $form) {
    $errors = [];
    $modelDirty = \Closure::bind(static function () use ($form): array {
        return $form->model->getEntry()->getDirty();
    }, null, Model::class)();
    foreach ($modelDirty as $key => $value) {
        // we should care only about editable fields
        if (View\Field::isEditable($form->model->getField($key))) {
            $errors[] = $form->error($key, 'Value was changed, ' . Webpage::encodeJson($form->model->getEntry()->getLoaded($key)) . ' to ' . Webpage::encodeJson($value));
        }
    }

    return $errors ?: 'No fields were changed';
});
