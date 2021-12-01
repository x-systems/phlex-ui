<?php

declare(strict_types=1);

namespace Phlex\Ui\Table\Column;

use Phlex\Data\Model;
use Phlex\Ui\Exception;
use Phlex\Ui\JsExpression;
use Phlex\Ui\Table;
use Phlex\Ui\Webpage;

/**
 * Implements Checkbox column for selecting rows.
 */
class Checkbox extends Table\Column
{
    public $class;

    /**
     * Return action which will calculate and return array of all Checkbox IDs, e.g.
     *
     * [3, 5, 20]
     */
    public function jsChecked()
    {
        return new JsExpression(' $(' . $this->table->jsRender() . ').find(\'.checked.' . $this->class . '\').closest(\'tr\').map(function(){ ' .
            'return $(this).data(\'id\');}).get().join(\',\')');
    }

    protected function doInitialize(): void
    {
        parent::doInitialize();
        if (!$this->class) {
            $this->class = 'cb_' . $this->elementId;
        }
    }

    public function getHeaderCellHtml(Model\Field $field = null, $value = null)
    {
        if (isset($field)) {
            throw (new Exception('Checkbox must be placed in an empty column. Don\'t specify any field.'))
                ->addMoreInfo('field', $field);
        }
        $this->table->js(true)->find('.' . $this->class)->checkbox();

        return parent::getHeaderCellHtml($field);
    }

    public function getDataCellTemplate(Model\Field $field = null)
    {
        return Webpage::getTag('div', ['class' => 'ui checkbox ' . $this->class], [['input', ['type' => 'checkbox']]]);
    }
}
