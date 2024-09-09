<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\Dropdown;
use Phlex\Ui\Form\Control\Input;
use Phlex\Ui\Header;
use Phlex\Ui\Menu;
use Phlex\Ui\View;
use Phlex\Ui\Webpage;

/**
 * Demonstrates how to use menu.
 */
/** @var Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

$menu = Menu::addTo($webpage);
$menu->addItem('foo', 'foo.php');
$menu->addItem('bar');
$menu->addItem('baz');
$dropdown = Dropdown::addTo($menu, ['With Callback', 'dropdownOptions' => ['on' => 'hover']]);
$dropdown->setSource(['a', 'b', 'c']);
$dropdown->onChange(static function ($itemId) {
    return 'New seleced item id: ' . $itemId;
});

$submenu = $menu->addMenu('Sub-menu');
$submenu->addItem('one', 'one.php');
$submenu->addItem(['two', 'label' => 'VIP', 'disabled']);

$submenu = $submenu->addMenu('Sub-menu');
$submenu->addItem('one');
$submenu->addItem('two');

$menu = Menu::addTo($webpage, ['vertical pointing']);
$menu->addItem(['Inbox', 'label' => ['123', 'teal left pointing']]);
$menu->addItem('Spam');
Input::addTo($menu->addItem(), ['placeholder' => 'Search', 'icon' => 'search'])->addClass('transparent');

$menu = Menu::addTo($webpage, ['secondary vertical pointing']);
$menu->addItem(['Inbox', 'label' => ['123', 'teal left pointing']]);
$menu->addItem('Spam');
Input::addTo($menu->addItem(), ['placeholder' => 'Search', 'icon' => 'search'])->addClass('transparent');
$menu = Menu::addTo($webpage, ['vertical']);
$group = $menu->addGroup('Products');
$group->addItem('Enterprise');
$group->addItem('Consumer');

$group = $menu->addGroup('Hosting');
$group->addItem('Shared');
$group->addItem('Dedicated');

$menu = Menu::addTo($webpage, ['vertical']);
$i = $menu->addItem();
Header::addTo($i, ['size' => 4])->set('Promotions');
View::addTo($i, ['element' => 'P'])->set('Check out our promotions');

// menu without any item should not show
Menu::addTo($webpage);
