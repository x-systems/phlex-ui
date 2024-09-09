<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\Columns;
use Phlex\Ui\Header;
use Phlex\Ui\Icon;
use Phlex\Ui\LoremIpsum;
use Phlex\Ui\Table;
use Phlex\Ui\View;
use Phlex\Ui\Webpage;

/**
 * Testing Columns.
 */
/** @var Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

// some custom style needed for our "highlight" to work. You don't need this on
// your page and it's bad style to include CSS like this!
$webpage->addStyle('
#example .highlight.grid .column:not(.row):not(.grid):after {
    background-color: rgba(86, 61, 124, .1);
    -webkit-box-shadow: 0px 0px 0px 1px rgba(86, 61, 124, 0.2) inset;
    box-shadow: 0px 0px 0px 1px rgba(86, 61, 124, 0.2) inset;
    content: "";
    display: block;
    min-height: 50px;
}
');

$page = View::addTo($webpage, ['id' => 'example']);

Header::addTo($page, ['Basic Usage']);

$c = Columns::addTo($page);
LoremIpsum::addTo($c->addColumn(), [1]);
LoremIpsum::addTo($c->addColumn(), [1]);
LoremIpsum::addTo($c->addColumn(), [1]);

Header::addTo($page, ['Specifying widths, using rows or automatic flow']);

// highlight class will show cells as boxes, even though they contain nothing
$c = Columns::addTo($page, [null, 'highlight']);
$c->addColumn(3);
$c->addColumn(5);
$c->addColumn(2);
$c->addColumn(6);
$c->addColumn(5);
$c->addColumn(2);
$c->addColumn(6);
$c->addColumn(3);

$r = $c->addRow();
$r->addColumn();
$r->addColumn();
$r->addColumn();

Header::addTo($page, ['Content Outline']);
$c = Columns::addTo($page, ['internally celled']);

$r = $c->addRow();
Icon::addTo($r->addColumn([2, 'right aligned']), ['huge home']);
LoremIpsum::addTo($r->addColumn(12), [1]);
Icon::addTo($r->addColumn(2), ['huge trash']);

$r = $c->addRow();
Icon::addTo($r->addColumn([2, 'right aligned']), ['huge home']);
LoremIpsum::addTo($r->addColumn(12), [1]);
Icon::addTo($r->addColumn(2), ['huge trash']);

Header::addTo($page, ['Add elements into columns and using classes']);

/**
 * Example box component with some content, good for putting into columns.
 */

/** @var View $boxClass */
$boxClass = get_class(new class() extends View {
    public $ui = 'segment';
    public $content = false;

    protected function doInitialize(): void
    {
        parent::doInitialize();
        Table::addTo($this, ['header' => false])
            ->setSource(['One', 'Two', 'Three', 'Four']);
    }
});

$c = Columns::addTo($page, ['width' => 4]);
$boxClass::addTo($c->addColumn(), [null, 'red']);
$boxClass::addTo($c->addColumn([null, null, 'right floated']), [null, 'blue']);
