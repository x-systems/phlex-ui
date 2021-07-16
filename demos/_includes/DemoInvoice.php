<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

/**
 * Invoice class for tutorial intro.
 */
class DemoInvoice extends \Phlex\Data\Model
{
    public $dateFormat;

    public $titleKey = 'reference';

    protected function doInitialize(): void
    {
        parent::doInitialize();

        $this->addField('reference', ['required' => true]);
        $this->addField('date', [
            'type' => 'date',
            'required' => true,
            'typecast' => [
                function ($v) {
                    return ($v instanceof \DateTime) ? date_format($v, $this->dateFormat) : $v;
                },
                function ($v) {
                    return $v;
                },
            ],
        ]);
    }
}
