<?php

declare(strict_types=1);

namespace Phlex\Ui\Table\Column;

use Phlex\Data\Model;
use Phlex\Ui\Table;

/**
 * Column for formatting money.
 */
class Money extends Table\Column
{
    /** @var bool Should we show zero values in cells? */
    public $show_zero_values = true;

    // overrides
    public $attr = ['all' => ['class' => ['right aligned single line']]];

    public function getTagAttributes($position, array $attr = []): array
    {
        $attr = array_merge_recursive($attr, ['class' => ['{$_' . $this->elementId . '_class}']]);

        return parent::getTagAttributes($position, $attr);
    }

    public function getDataCellHtml(Model\Field $field = null, $extra_tags = [])
    {
        if (!isset($field)) {
            throw new \Phlex\Ui\Exception('Money column requires a field');
        }

        return $this->getTag(
            'body',
            '{$' . $field->elementId . '}',
            $extra_tags
        );
    }

    public function getHtmlTags(Model $row, $field)
    {
        if ($field->get() < 0) {
            return ['_' . $this->elementId . '_class' => 'negative'];
        } elseif (!$this->show_zero_values && (float) $field->get() === 0.0) {
            return ['_' . $this->elementId . '_class' => '', $field->elementId => '-'];
        }

        return ['_' . $this->elementId . '_class' => ''];
    }
}
