<?php

declare(strict_types=1);

namespace Phlex\Ui\Tests;

use Phlex\Data\Model;
use Phlex\Data\Persistence;
use Phlex\Ui\Form;

class MyTestModel extends Model
{
    protected function doInitialize(): void
    {
        parent::doInitialize();

        $this->addField('regular_field');
        $this->addField('just_for_data', ['never_persist' => true]);
        $this->addField('no_persist_but_show_in_ui', ['never_persist' => true, 'ui' => ['editable' => true]]);
    }
}

/**
 * Test is designed to verify that field which is explicitly editable should appear and be editable
 * even if 'never_persist' is set to true.
 */
class ForFieldUiTest extends \Phlex\Core\PHPUnit\TestCase
{
    /** @var Model */
    public $m;

    protected function setUp(): void
    {
        $p = new Persistence\Array_();
        $this->m = new MyTestModel($p);
    }

    public function testModelLevel(): void
    {
        $this->assertTrue($this->m->getField('no_persist_but_show_in_ui')->isEditable());
    }

    public function testRegularField(): void
    {
        $f = new \Phlex\Ui\Form();
        $f->initialize();
        $f->setModel($this->m->createEntity());
        $this->assertFalse($f->getControl('regular_field')->readonly);
    }

    public function testJustDataField(): void
    {
        $f = new \Phlex\Ui\Form();
        $f->initialize();
        $f->setModel($this->m->createEntity(), ['just_for_data']);
        $this->assertTrue($f->getControl('just_for_data')->readonly);
    }

    public function testShowInUi(): void
    {
        $f = new \Phlex\Ui\Form();
        $f->initialize();
        $f->setModel($this->m->createEntity());
        $this->assertFalse($f->getControl('no_persist_but_show_in_ui')->readonly);
    }
}
