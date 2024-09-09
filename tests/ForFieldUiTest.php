<?php

declare(strict_types=1);

namespace Phlex\Ui\Tests;

use Phlex\Core\PHPUnit\TestCase;
use Phlex\Data\Model;
use Phlex\Data\Persistence;
use Phlex\Ui\Form;
use Phlex\Ui\View;

class MyTestModel extends Model
{
    protected function doInitialize(): void
    {
        parent::doInitialize();

        $this->addField('regular_field');
        $this->addField('just_for_data', ['never_persist' => true]);
        $this->addField('no_persist_but_show_in_ui', ['never_persist' => true, 'options' => [View\Field::OPTION_EDITABLE => true]]);
    }
}

/**
 * Test is designed to verify that field which is explicitly editable should appear and be editable
 * even if 'never_persist' is set to true.
 */
class ForFieldUiTest extends TestCase
{
    /** @var Model */
    public $model;

    protected function setUp(): void
    {
        $this->model = new MyTestModel(new Persistence\Array_());
    }

    public function testModelLevel(): void
    {
        $this->assertTrue(View\Field::isEditable($this->model->getField('no_persist_but_show_in_ui')));
    }

    public function testRegularField(): void
    {
        $form = new Form();
        $form->initialize();
        $form->setModel($this->model->createEntity());
        $this->assertFalse($form->getControl('regular_field')->readonly);
    }

    public function testJustDataField(): void
    {
        $form = new Form();
        $form->initialize();
        $form->setModel($this->model->createEntity(), ['just_for_data']);
        $this->assertTrue($form->getControl('just_for_data')->readonly);
    }

    public function testShowInUi(): void
    {
        $form = new Form();
        $form->initialize();
        $form->setModel($this->model);
        $this->assertFalse($form->getControl('no_persist_but_show_in_ui')->readonly);
    }
}
