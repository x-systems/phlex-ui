<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

/** @var \Phlex\Ui\Webpage $app */
require_once __DIR__ . '/../init-app.php';

// Re-usable component implementing counter

/** @var \Phlex\Ui\Columns $finderClass */
$finderClass = get_class(new class() extends \Phlex\Ui\Columns {
    public $route = [];

    public function setModel(\Phlex\Data\Model $model, $route = [])
    {
        parent::setModel($model);

        $this->addClass('internally celled');

        // lets add our first table here
        $table = \Phlex\Ui\Table::addTo($this->addColumn(), ['header' => false, 'very basic selectable'])->addStyle('cursor', 'pointer');
        $table->setModel($model, [$model->titleKey]);

        $selections = explode(',', $_GET[$this->name] ?? '');

        if (!empty($selections[0])) {
            $table->js(true)->find('tr[data-id=' . $selections[0] . ']')->addClass('active');
        }

        $path = [];
        $jsReload = new \Phlex\Ui\JsReload($this, [$this->name => new \Phlex\Ui\JsExpression('[]+[]', [
            $path ? (implode(',', $path) . ',') : '',
            new \Phlex\Ui\JsExpression('$(this).data("id")'),
        ])]);
        $table->on('click', 'tr', $jsReload);

        while ($selections && $id = array_shift($selections)) {
            $path[] = $id;
            $pushModel = new $model($model->persistence);
            $pushModel = $pushModel->tryLoad($id);
            if (!$pushModel->isLoaded()) {
                break;
            }
            $ref = array_shift($route);
            if (!$route) {
                $route[] = $ref; // repeat last route
            }

            if (!$pushModel->hasRef($ref)) {
                break; // no such route
            }

            $pushModel = $pushModel->ref($ref);

            $table = \Phlex\Ui\Table::addTo($this->addColumn(), ['header' => false, 'very basic selectable'])->addStyle('cursor', 'pointer');
            $table->setModel($pushModel->setLimit(10), [$pushModel->titleKey]);

            if ($selections) {
                $table->js(true)->find('tr[data-id=' . $selections[0] . ']')->addClass('active');
            }

            $jsReload = new \Phlex\Ui\JsReload($this, [$this->name => new \Phlex\Ui\JsExpression('[]+[]', [
                $path ? (implode(',', $path) . ',') : '',
                new \Phlex\Ui\JsExpression('$(this).data("id")'),
            ])]);
            $table->on('click', 'tr', $jsReload);
        }

        return $this->model;
    }
});

$model = new File($app->db);
$model->addCondition($model->key()->parent_folder_id, null);
$model->setOrder([$model->key()->is_folder => 'desc', $model->key()->name]);

\Phlex\Ui\Header::addTo($app, ['MacOS File Finder', 'subHeader' => 'Component built around Table, Columns and JsReload']);

$vp = \Phlex\Ui\VirtualPage::addTo($app)->set(function ($vp) use ($model) {
    $model->persistence->query($model)->delete()->execute();
    $model->importFromFilesystem('.');
    \Phlex\Ui\Button::addTo($vp, ['Import Complete', 'big green fluid'])->link('multitable.php');
    $vp->js(true)->closest('.modal')->find('.header')->remove();
});

\Phlex\Ui\Button::addTo($app, ['Re-Import From Filesystem', 'top attached'])->on('click', new \Phlex\Ui\JsModal('Now importing ... ', $vp));

$finderClass::addTo($app, ['bottom attached'])
    ->addClass('top attached segment')
    ->setModel($model->setLimit(5), [$model->key()->SubFolder]);
