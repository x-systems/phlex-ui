<?php

declare(strict_types=1);

namespace Phlex\Ui\Table\Column;

use Phlex\Data\Model;
use Phlex\Ui\Exception;
use Phlex\Ui\Table;
use Phlex\Ui\Webpage;

/**
 * Class Tooltip.
 *
 * column to add a little icon to show on hover a text
 * text is taken by the Row Model in $tooltip_field
 *
 * @usage   : $crud->addDecorator('paid_date',  new \Phlex\Ui\Table\Column\Tooltip('note'));
 *
 * @usage   : $crud->addDecorator('paid_date',  new \Phlex\Ui\Table\Column\Tooltip('note','error red'));
 */
class Tooltip extends Table\Column
{
    /**
     * @var string
     */
    public $icon;

    /**
     * @var string
     */
    public $tooltip_field;

    protected function doInitialize(): void
    {
        parent::doInitialize();

        if (!$this->icon) {
            $this->icon = 'info circle';
        }

        if (!$this->tooltip_field) {
            throw new Exception('Tooltip field must be defined');
        }
    }

    public function getDataCellHtml(Model\Field $field = null, $extra_tags = [])
    {
        if ($field === null) {
            throw new Exception('Tooltip can be used only with model field');
        }

        $key = $field->getKey();

        $attr = $this->getTagAttributes('body');

        $extra_tags = array_merge_recursive($attr, $extra_tags, ['class' => '{$_' . $key . '_tooltip}']);

        if (is_array($extra_tags['class'] ?? null)) {
            $extra_tags['class'] = implode(' ', $extra_tags['class']);
        }

        return Webpage::tag('td', $extra_tags, [
            ' {$' . $key . '}' . Webpage::getTag('span', [
                'class' => 'ui icon link {$_' . $key . '_data_visible_class}',
                'data-tooltip' => '{$_' . $key . '_data_tooltip}',
            ], [
                ['i', ['class' => 'ui icon {$_' . $key . '_icon}']],
            ]),
        ]);
    }

    public function getHtmlTags(Model $row, $field)
    {
        // @TODO remove popup tooltip when null
        $tooltip = $row->get($this->tooltip_field);

        $key = $field->getKey();

        if ($tooltip === null || $tooltip === '') {
            return [
                '_' . $key . '_data_visible_class' => 'transition hidden',
                '_' . $key . '_data_tooltip' => '',
                '_' . $key . '_icon' => '',
            ];
        }

        return [
            '_' . $key . '_data_visible_class' => '',
            '_' . $key . '_data_tooltip' => $tooltip,
            '_' . $key . '_icon' => $this->icon,
        ];
    }
}
