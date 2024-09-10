<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Data\Model;
use Phlex\Ui\Button;
use Phlex\Ui\Columns;
use Phlex\Ui\Header;
use Phlex\Ui\JsExpression;
use Phlex\Ui\JsModal;
use Phlex\Ui\JsReload;
use Phlex\Ui\Table;
use Phlex\Ui\VirtualPage;
use Phlex\Ui\Webpage;

/** @var Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

// Re-usable component implementing counter

/** @var Columns $finderClass */
$finderClass = get_class(new class() extends Columns {
    public $route = [];

    public function setModel(Model $model, $route = [])
    {
        parent::setModel($model);

        $this->addClass('internally celled');

        // lets add our first table here
        $table = Table::addTo($this->addColumn(), ['header' => false, 'very basic selectable'])->addStyle('cursor', 'pointer');
        $table->setModel($model, [$model->titleKey]);

        $selections = explode(',', $_GET[$this->elementName] ?? '');

        if (!empty($selections[0])) {
            $table->js(true)->find('tr[data-id=' . $selections[0] . ']')->addClass('active');
        }

        $path = [];
        $jsReload = new JsReload($this, [$this->elementName => new JsExpression('[]+[]', [
            $path ? (implode(',', $path) . ',') : '',
            new JsExpression('$(this).data("id")'),
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

            if (!$pushModel->hasField($ref)) {
                break; // no such route
            }

            $pushModel = $pushModel->ref($ref);

            $table = Table::addTo($this->addColumn(), ['header' => false, 'very basic selectable'])->addStyle('cursor', 'pointer');
            $table->setModel($pushModel->setLimit(10), [$pushModel->titleKey]);

            if ($selections) {
                $table->js(true)->find('tr[data-id=' . $selections[0] . ']')->addClass('active');
            }

            $jsReload = new JsReload($this, [$this->elementName => new JsExpression('[]+[]', [
                $path ? (implode(',', $path) . ',') : '',
                new JsExpression('$(this).data("id")'),
            ])]);
            $table->on('click', 'tr', $jsReload);
        }

        return $this->model;
    }
});

$model = new File($webpage->db);
$model->addCondition($model->key()->parent_folder_id, null);
$model->setOrder([$model->key()->is_folder => 'desc', $model->key()->name]);

Header::addTo($webpage, ['MacOS File Finder', 'subHeader' => 'Component built around Table, Columns and JsReload']);

$vp = VirtualPage::addTo($webpage)->set(function ($vp) use ($model) {
    $model->persistence->query($model)->delete()->execute();
    $model->importFromFilesystem('.');
    Button::addTo($vp, ['Import Complete', 'big green fluid'])->link('multitable.php');
    $vp->js(true)->closest('.modal')->find('.header')->remove();
});

Button::addTo($webpage, ['Re-Import From Filesystem', 'top attached'])->on('click', new JsModal('Now importing ... ', $vp));

$finderClass::addTo($webpage, ['bottom attached'])
    ->addClass('top attached segment')
    ->setModel($model->setLimit(5), [$model->key()->SubFolder]);
