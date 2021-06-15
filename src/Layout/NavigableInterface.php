<?php

declare(strict_types=1);
/**
 * Interface for a Layout using a navigable side menu.
 */

namespace Phlex\Ui\Layout;

use Phlex\Ui\Item;
use Phlex\Ui\Menu;

interface NavigableInterface
{
    /**
     * Add a group to left menu.
     */
    public function addMenuGroup($seed): Menu;

    /**
     * Add items to left menu.
     * Will place item in a group if supply.
     */
    public function addMenuItem($name, $action = null, $group = null): Item;
}
