<?php

declare(strict_types=1);

namespace Phlex\Ui\Layout;

use Phlex\Ui\Header;
use Phlex\Ui\Icon;
use Phlex\Ui\Item;
use Phlex\Ui\Jquery;
use Phlex\Ui\Menu;

/**
 * Implements a classic 100% width admin layout.
 *
 * Optional left menu in inverse with fixed width is most suitable for contextual navigation or
 *  providing core object list (e.g. folders in mail)
 *
 * Another menu on the top for actions that can have a pull-down menus.
 *
 * A top-right spot is for user icon or personal menu, labels or stats.
 *
 * On top of the content there is automated title showing page title but can also work as a bread-crumb or container for buttons.
 *
 * Footer for a short copyright notice and perhaps some debug elements.
 *
 * Spots:
 *  - LeftMenu  (has_menuLeft)
 *  - Menu
 *  - RightMenu (has_menuRight)
 *  - Footer
 *
 *  - Content
 */
class Admin extends \Phlex\Ui\Layout implements NavigableInterface
{
    public $menuLeft;    // vertical menu
    public $menu;        // horizontal menu
    public $menuRight;   // vertical pull-down

    public $burger = true;      // burger menu item

    /** @var bool Whether or not left Menu is visible on Page load. */
    public $isMenuLeftVisible = true;

    public $defaultTemplate = 'layout/admin.html';

    protected function doInitialize(): void
    {
        parent::doInitialize();

        if ($this->menu === null) {
            $this->menu = Menu::addTo(
                $this,
                ['inverted fixed horizontal phlex-admin-top-menu', 'element' => 'header'],
                ['TopMenu']
            );
            $this->burger = $this->menu->addItem(['class' => ['icon']]);
            $this->burger->on('click', [
                (new Jquery('.phlex-sidenav'))->toggleClass('visible'),
                (new Jquery('body'))->toggleClass('phlex-sidenav-visible'),
            ]);
            Icon::addTo($this->burger, ['content']);

            Header::addTo($this->menu, [$this->getApp()->title, 'size' => 4]);
        }

        if ($this->menuRight === null) {
            $this->menuRight = Menu::addTo($this->menu, ['ui' => false], ['RightMenu'])
                ->addClass('right menu')->removeClass('item');
        }

        if ($this->menuLeft === null) {
            $this->menuLeft = Menu::addTo($this, ['ui' => 'phlex-sidenav-content'], ['LeftMenu']);
        }

        $this->template->trySet('version', $this->getApp()->version);
    }

    /**
     * Add a group to left menu.
     */
    public function addMenuGroup($seed): Menu
    {
        return $this->menuLeft->addGroup($seed);
    }

    /**
     * Add items to left menu.
     */
    public function addMenuItem($name, $action = null, $group = null): Item
    {
        if ($group) {
            return $group->addItem($name, $action);
        }

        return $this->menuLeft->addItem($name, $action);
    }

    protected function doRender(): void
    {
        if ($this->menuLeft) {
            if (count($this->menuLeft->elements) === 0) {
                // no items were added, so lets add dashboard
                $this->menuLeft->addItem(['Dashboard', 'icon' => 'dashboard'], ['index']);
            }
            if (!$this->isMenuLeftVisible) {
                $this->template->tryDel('CssVisibility');
            }
        }

        parent::doRender();
    }
}
