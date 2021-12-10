<?php

declare(strict_types=1);

namespace Phlex\Ui\Table\Column;

use Phlex\Ui\Table;
use Phlex\Ui\Webpage;

/**
 * Implement drag handler column for sorting table.
 */
class DragHandler extends Table\Column
{
    public $class;
    public $tag = 'i';
    /** @var \Phlex\Ui\JsCallback */
    public $cb;

    protected function doInitialize(): void
    {
        parent::doInitialize();

        if (!$this->class) {
            $this->class = 'content icon';
        }
        $this->cb = \Phlex\Ui\JsSortable::addTo($this->table, ['handleClass' => 'atk-handle']);
    }

    /**
     * Callback when table has been reorder using handle.
     */
    public function onReorder(\Closure $fx)
    {
        $this->cb->onReorder($fx);
    }

    public function getDataCellTemplate(\Phlex\Data\Model\Field $field = null)
    {
        return Webpage::tag($this->tag, ['class' => $this->class . ' atk-handle', 'style' => 'cursor:pointer; color: #bcbdbd']);
    }
}
