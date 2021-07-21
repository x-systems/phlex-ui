<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Data\Model;
use Phlex\Data\Persistence;
use Phlex\Ui\Form;
use Phlex\Ui\Form\Control\Multiline;
use Phlex\Ui\Header;
use Phlex\Ui\JsExpression;
use Phlex\Ui\JsFunction;

/** @var \Phlex\Ui\Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

Header::addTo($webpage, ['Multiline form control', 'icon' => 'database', 'subHeader' => 'Collect/Edit multiple rows of table record.']);

$dateFormat = $webpage->ui_persistence->date_format;
$timeFormat = $webpage->ui_persistence->time_format;

/** @var Model $inventoryItemClass */
$inventoryItemClass = get_class(new class() extends Model {
    public $dateFormat;
    public $timeFormat;
    public $countryPersistence;

    protected function doInitialize(): void
    {
        parent::doInitialize();

        $this->addField('item', [
            'required' => true,
            'default' => 'item',
            'ui' => ['multiline' => [Multiline::TABLE_CELL => ['width' => 2]]],
        ]);
        $this->addField('inv_date', [
            'default' => date($this->dateFormat),
            'type' => 'date',
            'typecast' => [
                function ($v) {
                    return ($v instanceof \DateTime) ? date_format($v, $this->dateFormat) : $v;
                },
                function ($v) {
                    return $v;
                },
            ],
            'ui' => ['multiline' => [Multiline::TABLE_CELL => ['width' => 2]]],
        ]);
        $this->addField('inv_time', [
            'default' => date($this->timeFormat),
            'type' => 'time',
            'typecast' => [
                function ($v) {
                    return ($v instanceof \DateTime) ? date_format($v, $this->timeFormat) : $v;
                },
                function ($v) {
                    return $v;
                },
            ],
            'ui' => ['multiline' => [Multiline::TABLE_CELL => ['width' => 2]]],
        ]);
        $this->hasOne('country', [
            'model' => new Country($this->countryPersistence),
            'ui' => ['multiline' => [Multiline::TABLE_CELL => ['width' => 3]]],
        ]);
        $this->addField('qty', [
            'type' => 'integer',
            'caption' => 'Qty / Box',
            'default' => 1,
            'required' => true,
            'ui' => ['multiline' => [Multiline::TABLE_CELL => ['width' => 2]]],
        ]);
        $this->addField('box', [
            'type' => 'integer',
            'caption' => '# of Boxes',
            'default' => 1,
            'required' => true,
            'ui' => ['multiline' => [Multiline::TABLE_CELL => ['width' => 2]]],
        ]);
        $this->addExpression('total', [
            'expr' => function (Model $row) {
                return $row->get('qty') * $row->get('box');
            },
            'type' => 'integer',
            'ui' => ['multiline' => [Multiline::TABLE_CELL => ['width' => 1, 'class' => 'blue']]],
        ]);
    }
});

$inventory = new $inventoryItemClass(new Persistence\Array_(), ['dateFormat' => $dateFormat, 'timeFormat' => $timeFormat, 'countryPersistence' => $webpage->db]);

// Populate some data.
$total = 0;
for ($i = 1; $i < 3; ++$i) {
    $entity = $inventory->createEntity();
    $entity->set('id', $i);
    $entity->set('inv_date', date($dateFormat));
    $entity->set('inv_time', date($timeFormat));
    $entity->set('item', 'item_' . $i);
    $entity->set('country', random_int(1, 100));
    $entity->set('qty', random_int(10, 100));
    $entity->set('box', random_int(1, 10));
    $total = $total + ($entity->get('qty') * $entity->get('box'));
    $entity->saveAndUnload();
}

$form = Form::addTo($webpage);

// Add multiline field and set model.
$multiline = $form->addControl('ml', [Multiline::class, 'tableProps' => ['color' => 'blue'], 'itemLimit' => 10, 'addOnTab' => true]);
$multiline->setModel($inventory);

// Add total field.
$sublayout = $form->layout->addSubLayout([Form\Layout\Section\Columns::class]);
$sublayout->addColumn(12);
$column = $sublayout->addColumn(4);
$controlTotal = $column->addControl('total', ['readonly' => true])->set($total);

// Update total when qty and box value in any row has changed.
$multiline->onLineChange(function ($rows, $form) use ($controlTotal) {
    $total = 0;
    foreach ($rows as $row => $cols) {
        $qty = $cols['qty'] ?? 0;
        $box = $cols['box'] ?? 0;
        $total = $total + ($qty * $box);
    }

    return $controlTotal->jsInput()->val($total);
}, ['qty', 'box']);

$multiline->jsAfterAdd = new JsFunction(['value'], [new JsExpression('console.log(value)')]);
$multiline->jsAfterDelete = new JsFunction(['value'], [new JsExpression('console.log(value)')]);

$form->onSubmit(function (Form $form) use ($multiline) {
    $rows = $multiline->saveRows()->getModel()->export();

    return new \Phlex\Ui\JsToast($form->getApp()->encodeJson(array_values($rows)));
});
