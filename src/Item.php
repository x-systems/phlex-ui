<?php

declare(strict_types=1);

namespace Phlex\Ui;

/**
 * Place menu.
 */
class Item extends View
{
    /**
     * Specify a label for this menu item.
     *
     * @var string
     */
    public $label;

    /**
     * Specify icon for this menu item.
     *
     * @var string
     */
    public $icon;

    protected function doRender(): void
    {
        if ($this->label) {
            Label::addTo($this, [$this->label]);
        }

        if ($this->icon) {
            Icon::addTo($this, [$this->icon]);
        }

        parent::doRender();
    }
}
