<?php

declare(strict_types=1);

namespace Phlex\Ui\Table\Column;

use Phlex\Data\Model\Field;
use Phlex\Ui\CallbackLater;
use Phlex\Ui\Jquery;
use Phlex\Ui\Table;
use Phlex\Ui\Webpage;

/**
 * Formatting action buttons column.
 */
class Delete extends Table\Column
{
    protected function doInitialize(): void
    {
        parent::doInitialize();

        $this->vp = $this->table->addView(new CallbackLater());
        $this->vp->set(function () {
            $this->table->model->load($_POST[$this->elementName])->delete();

            $reload = $this->table->reload ?: $this->table;

            $this->table->getApp()->terminateJson($reload);
        });
    }

    public function getDataCellTemplate(Field $field = null)
    {
        $this->table->on('click', 'a.' . $this->elementId, null, ['confirm' => (new Jquery())->attr('title')])->phlexAjaxec([
            'uri' => $this->vp->getJsUrl(),
            'uri_options' => [$this->elementName => $this->table->jsRow()->data('id')],
        ]);

        return Webpage::tag(
            'a',
            ['href' => '#', 'title' => 'Delete {$' . $this->table->model->titleKey . '}?', 'class' => $this->elementId],
            [
                ['i', ['class' => 'ui red trash icon'], ''],
                'Delete',
            ]
        );
    }
}
