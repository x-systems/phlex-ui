<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\Button;
use Phlex\Ui\Form;
use Phlex\Ui\HtmlTemplate;

/**
 * Demonstrates how to use fields with form.
 */
/** @var \Phlex\Ui\Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

\Phlex\Ui\Header::addTo($webpage, ['Disabled and read only form controls (normal / readonly / disabled)']);

$form = Form::addTo($webpage);

// Test all kinds of input fields
$group = $form->addGroup('Line');
$group->addControl('line_norm')->set('editable');
$group->addControl('line_read', ['readonly' => true])->set('read only');
$group->addControl('line_disb', ['disabled' => true])->set('disabled');

$group = $form->addGroup('Text Area');
$group->addControl('text_norm', [Form\Control\Textarea::class])->set('editable');
$group->addControl('text_read', [Form\Control\Textarea::class, 'readonly' => true])->set('read only');
$group->addControl('text_disb', [Form\Control\Textarea::class, 'disabled' => true])->set('disabled');

$group = $form->addGroup('Checkbox');
$group->addControl('c_norm', [Form\Control\Checkbox::class])->set(true);
$group->addControl('c_read', [Form\Control\Checkbox::class, 'readonly' => true])->set(true); // allows to change value
$group->addControl('c_disb', [Form\Control\Checkbox::class, 'disabled' => true])->set(true); // input is not disabled

$group = $form->addGroup('Dropdown');
$values = [
    'tag' => ['Tag', 'icon' => 'tag icon'],
    'globe' => ['Globe', 'icon' => 'globe icon'],
    'registered' => ['Registered', 'icon' => 'registered icon'],
    'file' => ['File', 'icon' => 'file icon'],
];
$group->addControl('d_norm', [Form\Control\Dropdown::class, 'width' => 'three'], ['type' => ['enum', 'valuesWithLabels' => $values]])->set('globe');
$group->addControl('d_read', [Form\Control\Dropdown::class, 'readonly' => true, 'width' => 'three'], ['type' => ['enum', 'valuesWithLabels' => $values]])->set('globe'); // allows to change value
$group->addControl('d_disb', [Form\Control\Dropdown::class, 'disabled' => true, 'width' => 'three'], ['type' => ['enum', 'valuesWithLabels' => $values]])->set('globe'); // css disabled, but can focus with Tab and change value

$group = $form->addGroup('Radio');

$group->addControl('radio_norm', [Form\Control\Radio::class], ['type' => ['enum', 'values' => ['one', 'two', 'three']]])->set('two');
$group->addControl('radio_read', [Form\Control\Radio::class, 'readonly' => true], ['type' => ['enum', 'values' => ['one', 'two', 'three']]])->set('two');
$group->addControl('radio_disb', [Form\Control\Radio::class, 'disabled' => true], ['type' => ['enum', 'values' => ['one', 'two', 'three']]])->set('two');

$group = $form->addGroup('File upload');

$onDelete = function () {
};
$onUpload = function () {
};

$control = $group->addControl('file_norm', [Form\Control\Upload::class, ['accept' => ['.png', '.jpg']]])->set('normal', 'normal.jpg');
$control->onDelete($onDelete);
$control->onUpload($onUpload);

$control = $group->addControl('file_read', [Form\Control\Upload::class, ['accept' => ['.png', '.jpg'], 'readonly' => true]])->set('readonly', 'readonly.jpg');
$control->onDelete($onDelete);
$control->onUpload($onUpload);

$control = $group->addControl('file_disb', [Form\Control\Upload::class, ['accept' => ['.png', '.jpg'], 'disabled' => true]])->set('disabled', 'disabled.jpg');
$control->onDelete($onDelete);
$control->onUpload($onUpload);

$group = $form->addGroup('Lookup');

$model = new Country($webpage->db);

$group->addControl('Lookup_norm', [
    DemoLookup::class,
    'model' => new CountryLock($webpage->db),
    'plus' => true,
])->set($model->loadAny()->getId());

$group->addControl('Lookup_read', [
    Form\Control\Lookup::class,
    'model' => new CountryLock($webpage->db),
    'plus' => true,
    'readonly' => true,
])->set($model->loadAny()->getId());

$group->addControl('Lookup_disb', [
    Form\Control\Lookup::class,
    'model' => new CountryLock($webpage->db),
    'plus' => true,
    'disabled' => true,
])->set($model->loadAny()->getId());

$group = $form->addGroup('Calendar');

$group->addControl('date_norm', [Form\Control\Calendar::class, 'type' => 'date'])->set(date('Y-m-d'));
$group->addControl('date_read', [Form\Control\Calendar::class, 'type' => 'date', 'readonly' => true])->set(date('Y-m-d'));
$group->addControl('date_disb', [Form\Control\Calendar::class, 'type' => 'date', 'disabled' => true])->set(date('Y-m-d'));

$form->onSubmit(function (Form $form) {
});

\Phlex\Ui\Header::addTo($webpage, ['Stand Alone Line']);
// you can pass values to button
$control = Form\Control\Line::addTo($webpage);

$control->set('hello world');

$button = $control->addAction('check value');
$button->on('click', new \Phlex\Ui\JsExpression('alert("field value is: "+[])', [$control->jsInput()->val()]));

\Phlex\Ui\Header::addTo($webpage, ['Line in a Form']);
$form = Form::addTo($webpage);

$control = $form->addControl('Title', null, ['type' => ['enum', 'values' => ['Mr', 'Mrs', 'Miss']], 'options' => [
    Form\Control::OPTION_SEED => ['hint' => 'select one'],
]]);

$control = $form->addControl('name', [Form\Control\Line::class, 'hint' => 'this is sample hint that escapes <html> characters']);
$control->set('value in a form');

$control = $form->addControl('surname', new Form\Control\Line([
    'hint' => [\Phlex\Ui\View::class, 'template' => new HtmlTemplate(
        'Click <a href="http://example.com/" target="_blank">here</a>'
    )],
]));

$form->onSubmit(function (Form $form) {
    return $form->model->get('name');
});

\Phlex\Ui\Header::addTo($webpage, ['Multiple Form Layouts']);

$form = Form::addTo($webpage);
$tabs = \Phlex\Ui\Tabs::addTo($form, [], ['AboveControls']);
\Phlex\Ui\View::addTo($form, ['ui' => 'divider'], ['AboveControls']);

$formPage = Form\Layout::addTo($tabs->addTab('Basic Info'), ['form' => $form]);
$formPage->addControl('name', new Form\Control\Line());

$formPage = Form\Layout::addTo($tabs->addTab('Other Info'), ['form' => $form]);
$formPage->addControl('age', new Form\Control\Line());

$form->onSubmit(function (Form $form) {
    return $form->model->get('name') . ' has age ' . $form->model->get('age');
});

\Phlex\Ui\Header::addTo($webpage, ['onChange event', 'subHeader' => 'see in browser console']);

$form = Form::addTo($webpage);

$group = $form->addGroup('Calendar');
$c1 = $group->addControl('c1', new Form\Control\Calendar(['type' => 'date']));
$c2 = $group->addControl('c2', new Form\Control\Calendar(['type' => 'date']));
$c3 = $group->addControl('c3', new Form\Control\Calendar(['type' => 'date']));

$c1->onChange('console.log("c1 changed: "+date+","+text+","+mode)');
$c2->onChange(new \Phlex\Ui\JsExpression('console.log("c2 changed: "+date+","+text+","+mode)'));
$c3->onChange([
    new \Phlex\Ui\JsExpression('console.log("c3 changed: "+date+","+text+","+mode)'),
    new \Phlex\Ui\JsExpression('console.log("c3 really changed: "+date+","+text+","+mode)'),
]);

$group = $form->addGroup('Line');
$f1 = $group->addControl('f1');
$f2 = $group->addControl('f2');
$f3 = $group->addControl('f3');
$f4 = $group->addControl('f4');

$f1->onChange('console.log("f1 changed")');
$f2->onChange(new \Phlex\Ui\JsExpression('console.log("f2 changed")'));
$f3->onChange([
    new \Phlex\Ui\JsExpression('console.log("f3 changed")'),
    new \Phlex\Ui\JsExpression('console.log("f3 really changed")'),
]);
$f4->onChange(function () {
    return new \Phlex\Ui\JsExpression('console.log("f4 changed")');
});

$group = $form->addGroup('CheckBox');
$b1 = $group->addControl('b1', new Form\Control\Checkbox());
$b1->onChange('console.log("b1 changed")');

$group = $form->addGroup(['Dropdown', 'width' => 'three']);
$d1 = $group->addControl('d1', new Form\Control\Dropdown(['values' => [
    'tag' => ['Tag', 'icon' => 'tag icon'],
    'globe' => ['Globe', 'icon' => 'globe icon'],
    'registered' => ['Registered', 'icon' => 'registered icon'],
    'file' => ['File', 'icon' => 'file icon'],
],
]));
$d1->onChange('console.log("Dropdown changed")');

$group = $form->addGroup('Radio');
$r1 = $group->addControl('r1', new Form\Control\Radio(['values' => [
    'Tag',
    'Globe',
    'Registered',
    'File',
],
]));
$r1->onChange('console.log("radio changed")');

\Phlex\Ui\Header::addTo($webpage, ['Line ends of Textarea']);

$form = Form::addTo($webpage);
$group = $form->addGroup('Without model');
$group->addControl('text_crlf', [Form\Control\Textarea::class])->set("First line\r\nSecond line");
$group->addControl('text_cr', [Form\Control\Textarea::class])->set("First line\rSecond line");
$group->addControl('text_lf', [Form\Control\Textarea::class])->set("First line\nSecond line");

$group = $form->addGroup('With model');
$group->addControl('m_text_crlf', [Form\Control\Textarea::class], ['type' => 'text'])->set("First line\r\nSecond line");
$group->addControl('m_text_cr', [Form\Control\Textarea::class], ['type' => 'text'])->set("First line\rSecond line");
$group->addControl('m_text_lf', [Form\Control\Textarea::class], ['type' => 'text'])->set("First line\nSecond line");

$form->onSubmit(function (Form $form) {
    // check what values are submitted
    echo "We're URL encoding submitted values to be able to see what line end is actually submitted.";
    foreach ($form->model->get() as $k => $v) {
        var_dump([$k => urlencode($v)]);
    }
    echo 'As you can see - without model it submits CRLF, but with model it will normalize all to LF';
});
