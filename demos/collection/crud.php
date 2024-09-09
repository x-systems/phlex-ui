<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Data\Model;
use Phlex\Ui\Columns;
use Phlex\Ui\Crud;
use Phlex\Ui\Form;
use Phlex\Ui\Grid;
use Phlex\Ui\Header;
use Phlex\Ui\Icon;
use Phlex\Ui\JsToast;
use Phlex\Ui\Message;
use Phlex\Ui\Table\Column\Link;
use Phlex\Ui\UserAction\ModalExecutor;
use Phlex\Ui\View;
use Phlex\Ui\Webpage;

/** @var Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

$model = new CountryLock($webpage->db);

$crud = Crud::addTo($webpage, ['ipp' => 10]);

// callback for model action add form.
$crud->onFormAdd(static function ($form, $t) use ($model) {
    $form->js(true, $form->getControl($model->key()->name)->jsInput()->val('Entering value via javascript'));
});

// callback for model action edit form.
$crud->onFormEdit(static function ($form) use ($model) {
    $form->js(true, $form->getControl($model->key()->name)->jsInput()->attr('readonly', true));
});

// callback for both model action edit and add.
$crud->onFormAddEdit(static function ($form, $ex) {
    $form->onSubmit(static function (Form $form) use ($ex) {
        return [$ex->hide(), new JsToast('Submit all right! This demo does not saved data.')];
    });
});

$crud->setModel($model);

$crud->addDecorator($model->titleKey, [Link::class, ['test' => false, 'path' => 'interfaces/page'], ['_id' => 'id']]);

View::addTo($webpage, ['ui' => 'divider']);

$columns = Columns::addTo($webpage);
$column = $columns->addColumn(0, 'ui blue segment');

// Crud can operate with various fields
Header::addTo($column, ['Configured Crud']);
$crud = Crud::addTo($column, [
    'displayFields' => [$model->key()->name], // field to display in Crud
    'editFields' => [$model->key()->name, $model->key()->iso, $model->key()->iso3], // field to display on 'edit' action
    'ipp' => 5,
    'paginator' => ['range' => 2, 'class' => ['blue inverted']],  // reduce range on the paginator
    'menu' => ['class' => ['green inverted']],
    'table' => ['class' => ['red inverted']],
]);
// Condition on the model can be applied on a model
$model = new CountryLock($webpage->db);
$model->addCondition($model->key()->numcode, '<', 200);
$model->onHook(Model::HOOK_VALIDATE, static function ($model, $intent) {
    $err = [];
    if ($model->numcode >= 200) {
        $err[$model->key()->numcode] = 'Should be less than 200';
    }

    return $err;
});
$crud->setModel($model);

// Because Crud inherits Grid, you can also define custom actions
$crud->addModalAction(['icon' => [Icon::class, 'cogs']], 'Details', static function ($p, $id) use ($crud) {
    $model = CountryLock::assertInstanceOf($crud->model);
    Message::addTo($p, ['Details for: ' . $model->load($id)->name . ' (id: ' . $id . ')']);
});

$column = $columns->addColumn();
Header::addTo($column, ['Customizations']);

/** @var ModalExecutor $myExecutorClass */
$myExecutorClass = get_class(new class() extends ModalExecutor {
    public function addFormTo(View $view): Form
    {
        $columns = Columns::addTo($view);
        $left = $columns->addColumn();
        $right = $columns->addColumn();

        $result = parent::addFormTo($left);

        if ($this->action->getEntity()->get(File::hint()->key()->is_folder)) {
            Grid::addTo($right, ['menu' => false, 'ipp' => 5])
                ->setModel(File::assertInstanceOf($this->action->getModel())->SubFolder);
        } else {
            Message::addTo($right, ['Not a folder', 'warning']);
        }

        return $result;
    }
});

$file = new FileLock($webpage->db);
$webpage->getExecutorFactory()->registerExecutor($file->getUserAction('edit'), [$myExecutorClass]);

$crud = Crud::addTo($column, [
    'ipp' => 5,
]);

$crud->menu->addItem(['Rescan', 'icon' => 'recycle']);

// Condition on the model can be applied after setting the model
$crud->setModel($file)->addCondition($file->key()->parent_folder_id, null);
