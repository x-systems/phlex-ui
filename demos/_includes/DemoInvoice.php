<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Data\Model;
use Phlex\Data\Persistence;

/**
 * Invoice class for tutorial intro.
 */
class DemoInvoice extends Model
{
    public $dateFormat;

    public $titleKey = 'reference';

    protected function doInitialize(): void
    {
        parent::doInitialize();

        $this->addField('reference', ['required' => true]);
        $this->addField('date', [
            'type' => ['date', 'codec' => [
                Persistence\Sql\Codec\Dynamic::class,
                'encodeFx' => fn ($value) => ($value instanceof \DateTime) ? date_format($value, $this->dateFormat) : $value,
                'decodeFx' => fn ($value) => $value,
            ]],
            'required' => true,
        ]);
    }
}
