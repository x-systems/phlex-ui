<?php

declare(strict_types=1);
/**
 * An Admin layout with enhance left menu.
 * This layout uses jQuery plugin phlex-sidenav.plugin.js
 *  Default value for this plugin is set for Maestro layout using maestro-sidenav.html template.
 *  Note that it is possible to change these default value if another template is use.
 */

namespace Phlex\Ui\Layout;

use Phlex\Ui\Item;
use Phlex\Ui\Jquery;
use Phlex\Ui\Menu;

class Maestro extends Admin
{
    public $menuTemplate = 'layout/maestro-sidenav.html';

    public function addMenuGroup($seed): Menu
    {
        $gr = $this->menuLeft->addGroup($seed, $this->menuTemplate)->addClass('phlex-maestro-sidenav');
        $gr->removeClass('item');

        return $gr;
    }

    public function addMenuItem($name, $action = null, $group = null): Item
    {
        $i = parent::addMenuItem($name, $action, $group);
        if (!$group) {
            $i->addClass('phlex-maestro-sidenav');
        }

        return $i;
    }

    protected function doRender(): void
    {
        parent::doRender();

        // initialize all menu group at ounce.
        // since phlexSideNav plugin default setting are for Maestro, no need to pass settings to initialize it.
        $js = (new Jquery('.phlex-maestro-sidenav'))->phlexSidenav();

        $this->js(true, $js);
    }
}
