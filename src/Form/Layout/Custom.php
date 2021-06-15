<?php

declare(strict_types=1);

namespace Phlex\Ui\Form\Layout;

use Phlex\Core\Factory;
use Phlex\Ui\Exception;
use Phlex\Ui\Form\AbstractLayout;

/**
 * Custom Layout for a form (user-defined HTML).
 */
class Custom extends AbstractLayout
{
    /** @var string */
    public $defaultTemplate;

    protected function doInitialize(): void
    {
        parent::doInitialize();

        if (!$this->template) {
            throw new Exception('You must specify template for Form/Layout/Custom. Try [\'Custom\', \'defaultTemplate\'=>\'./yourform.html\']');
        }
    }

    /**
     * Adds Button into {$Buttons}.
     *
     * @param \Phlex\Ui\Button|array|string $seed
     *
     * @return \Phlex\Ui\Button
     */
    public function addButton($seed)
    {
        return $this->add(Factory::mergeSeeds([\Phlex\Ui\Button::class], $seed), 'Buttons');
    }
}
