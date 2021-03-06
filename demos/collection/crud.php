<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

/** @var \Phlex\Ui\App $app */
require_once __DIR__ . '/../init-app.php';

$model = new CountryLock($app->db);

$crud = \Phlex\Ui\Crud::addTo($app, ['ipp' => 10]);

// callback for model action add form.
$crud->onFormAdd(function ($form, $t) use ($model) {
    $form->js(true, $form->getControl($model->fieldName()->name)->jsInput()->val('Entering value via javascript'));
});

// callback for model action edit form.
$crud->onFormEdit(function ($form) use ($model) {
    $form->js(true, $form->getControl($model->fieldName()->name)->jsInput()->attr('readonly', true));
});

// callback for both model action edit and add.
$crud->onFormAddEdit(function ($form, $ex) {
    $form->onSubmit(function (\Phlex\Ui\Form $form) use ($ex) {
        return [$ex->hide(), new \Phlex\Ui\JsToast('Submit all right! This demo does not saved data.')];
    });
});

$crud->setModel($model);

$crud->addDecorator($model->title_field, [\Phlex\Ui\Table\Column\Link::class, ['test' => false, 'path' => 'interfaces/page'], ['_id' => 'id']]);

\Phlex\Ui\View::addTo($app, ['ui' => 'divider']);

$columns = \Phlex\Ui\Columns::addTo($app);
$column = $columns->addColumn(0, 'ui blue segment');

// Crud can operate with various fields
\Phlex\Ui\Header::addTo($column, ['Configured Crud']);
$crud = \Phlex\Ui\Crud::addTo($column, [
    'displayFields' => [$model->fieldName()->name], // field to display in Crud
    'editFields' => [$model->fieldName()->name, $model->fieldName()->iso, $model->fieldName()->iso3], // field to display on 'edit' action
    'ipp' => 5,
    'paginator' => ['range' => 2, 'class' => ['blue inverted']],  // reduce range on the paginator
    'menu' => ['class' => ['green inverted']],
    'table' => ['class' => ['red inverted']],
]);
// Condition on the model can be applied on a model
$model = new CountryLock($app->db);
$model->addCondition($model->fieldName()->numcode, '<', 200);
$model->onHook(\Phlex\Data\Model::HOOK_VALIDATE, function ($model, $intent) {
    $err = [];
    if ($model->numcode >= 200) {
        $err[$model->fieldName()->numcode] = 'Should be less than 200';
    }

    return $err;
});
$crud->setModel($model);

// Because Crud inherits Grid, you can also define custom actions
$crud->addModalAction(['icon' => [\Phlex\Ui\Icon::class, 'cogs']], 'Details', function ($p, $id) use ($crud) {
    $model = CountryLock::assertInstanceOf($crud->model);
    \Phlex\Ui\Message::addTo($p, ['Details for: ' . $model->load($id)->name . ' (id: ' . $id . ')']);
});

$column = $columns->addColumn();
\Phlex\Ui\Header::addTo($column, ['Customizations']);

/** @var \Phlex\Ui\UserAction\ModalExecutor $myExecutorClass */
$myExecutorClass = get_class(new class() extends \Phlex\Ui\UserAction\ModalExecutor {
    public function addFormTo(\Phlex\Ui\View $view): \Phlex\Ui\Form
    {
        $columns = \Phlex\Ui\Columns::addTo($view);
        $left = $columns->addColumn();
        $right = $columns->addColumn();

        $result = parent::addFormTo($left);

        if ($this->action->getModel()->get(File::hinting()->fieldName()->is_folder)) {
            \Phlex\Ui\Grid::addTo($right, ['menu' => false, 'ipp' => 5])
                ->setModel(File::assertInstanceOf($this->action->getModel())->SubFolder);
        } else {
            \Phlex\Ui\Message::addTo($right, ['Not a folder', 'warning']);
        }

        return $result;
    }
});

$file = new FileLock($app->db);
$app->getExecutorFactory()->registerExecutor($file->getUserAction('edit'), [$myExecutorClass]);

$crud = \Phlex\Ui\Crud::addTo($column, [
    'ipp' => 5,
]);

$crud->menu->addItem(['Rescan', 'icon' => 'recycle']);

// Condition on the model can be applied after setting the model
$crud->setModel($file)->addCondition($file->fieldName()->parent_folder_id, null);
