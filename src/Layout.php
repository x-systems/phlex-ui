<?php

declare(strict_types=1);

namespace Phlex\Ui;

class Layout extends View
{
    /**
     * Add a loadable View.
     */
    public function addRightPanel(Panel\Loadable $panel): Panel\Loadable
    {
        return $this->getOwner()->addView($panel, 'RightPanel');
    }
}
