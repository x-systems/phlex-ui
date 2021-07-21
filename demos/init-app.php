<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

date_default_timezone_set('UTC');

require_once __DIR__ . '/init-autoloader.php';

// collect coverage for HTTP tests 1/2
if (file_exists(__DIR__ . '/CoverageUtil.php') && !class_exists(\PHPUnit\Framework\TestCase::class, false)) {
    require_once __DIR__ . '/CoverageUtil.php';
    \CoverageUtil::start();
}

$webpage = new \Phlex\Ui\Webpage([
    'call_exit' => (bool) ($_GET['APP_CALL_EXIT'] ?? true),
    'catch_exceptions' => (bool) ($_GET['APP_CATCH_EXCEPTIONS'] ?? true),
    'always_run' => (bool) ($_GET['APP_ALWAYS_RUN'] ?? true),
]);
$webpage->title = 'Phlex UI Demo v' . $webpage->version;

if ($webpage->call_exit !== true) {
    $webpage->stickyGet('APP_CALL_EXIT');
}

if ($webpage->catch_exceptions !== true) {
    $webpage->stickyGet('APP_CATCH_EXCEPTIONS');
}

// collect coverage for HTTP tests 2/2
if (file_exists(__DIR__ . '/CoverageUtil.php') && !class_exists(\PHPUnit\Framework\TestCase::class, false)) {
    $webpage->onHook(\Phlex\Ui\Webpage::HOOK_BEFORE_EXIT, function () {
        \CoverageUtil::saveData();
    });
}

try {
    /** @var \Phlex\Data\Persistence\Sql $db */
    require_once __DIR__ . '/init-db.php';
    $webpage->db = $db;
    unset($db);
} catch (\Throwable $e) {
    throw new \Phlex\Ui\Exception('Database error: ' . $e->getMessage());
}

[$rootUrl, $relUrl] = preg_split('~(?<=/)(?=demos(/|\?|$))|\?~s', $_SERVER['REQUEST_URI'], 3);
$demosUrl = $rootUrl . 'demos/';

if (file_exists(__DIR__ . '/../public/atkjs-ui.min.js')) {
    $webpage->cdn['atk'] = $rootUrl . 'public';
}

// allow custom layout override
$webpage->initBody([$webpage->stickyGet('layout') ?? \Phlex\Ui\Layout\Maestro::class]);

$body = $webpage->body;
if ($body instanceof \Phlex\Ui\Layout\NavigableInterface) {
    $body->addMenuItem(['Welcome to Phlex', 'icon' => 'gift'], [$demosUrl . 'index']);

    $path = $demosUrl . 'layout/';
    $menu = $body->addMenuGroup(['Layout', 'icon' => 'object group']);
    $body->addMenuItem(['Layouts'], [$path . 'layouts'], $menu);
    $body->addMenuItem(['Sliding Panel'], [$path . 'layout-panel'], $menu);

    $path = $demosUrl . 'basic/';
    $menu = $body->addMenuGroup(['Basics', 'icon' => 'cubes']);
    $body->addMenuItem('View', [$path . 'view'], $menu);
    $body->addMenuItem('Button', [$path . 'button'], $menu);
    $body->addMenuItem('Header', [$path . 'header'], $menu);
    $body->addMenuItem('Message', [$path . 'message'], $menu);
    $body->addMenuItem('Labels', [$path . 'label'], $menu);
    $body->addMenuItem('Menu', [$path . 'menu'], $menu);
    $body->addMenuItem('Breadcrumb', [$path . 'breadcrumb'], $menu);
    $body->addMenuItem(['Columns'], [$path . 'columns'], $menu);
    $body->addMenuItem(['Grid Layout'], [$path . 'grid-layout'], $menu);

    $path = $demosUrl . 'form/';
    $menu = $body->addMenuGroup(['Form', 'icon' => 'edit']);
    $body->addMenuItem('Basics and Layouting', [$path . 'form'], $menu);
    $body->addMenuItem('Data Integration', [$path . 'form2'], $menu);
    $body->addMenuItem(['Form Sections'], [$path . 'form-section'], $menu);
    $body->addMenuItem('Form Multi-column layout', [$path . 'form3'], $menu);
    $body->addMenuItem(['Integration with Columns'], [$path . 'form5'], $menu);
    $body->addMenuItem(['HTML Layout'], [$path . 'html-layout'], $menu);
    $body->addMenuItem(['Conditional Fields'], [$path . 'jscondform'], $menu);

    $path = $demosUrl . 'form-control/';
    $menu = $body->addMenuGroup(['Form Controls', 'icon' => 'keyboard outline']);
    $body->addMenuItem(['Input'], [$path . 'input2'], $menu);
    $body->addMenuItem('Input Decoration', [$path . 'input'], $menu);
    $body->addMenuItem('Calendar', [$path . 'calendar'], $menu);
    $body->addMenuItem(['Checkboxes'], [$path . 'checkbox'], $menu);
    $body->addMenuItem(['Value Selectors'], [$path . 'form6'], $menu);
    $body->addMenuItem(['Lookup'], [$path . 'lookup'], $menu);
    $body->addMenuItem(['Lookup Dependency'], [$path . 'lookup-dep'], $menu);
    $body->addMenuItem(['Dropdown'], [$path . 'dropdown-plus'], $menu);
    $body->addMenuItem(['File Upload'], [$path . 'upload'], $menu);
    $body->addMenuItem(['Multi Line'], [$path . 'multiline'], $menu);
    $body->addMenuItem(['Tree Selector'], [$path . 'tree-item-selector'], $menu);
    $body->addMenuItem(['Scope Builder'], [$path . 'scope-builder'], $menu);

    $path = $demosUrl . 'collection/';
    $menu = $body->addMenuGroup(['Data Collection', 'icon' => 'table']);
    $body->addMenuItem('Data table with formatted columns', [$path . 'table'], $menu);
    $body->addMenuItem(['Advanced table examples'], [$path . 'table2'], $menu);
    $body->addMenuItem('Table interractions', [$path . 'multitable'], $menu);
    $body->addMenuItem(['Column Menus'], [$path . 'tablecolumnmenu'], $menu);
    $body->addMenuItem(['Column Filters'], [$path . 'tablefilter'], $menu);
    $body->addMenuItem('Grid - Table+Bar+Search+Paginator', [$path . 'grid'], $menu);
    $body->addMenuItem('Crud - Full editing solution', [$path . 'crud'], $menu);
    $body->addMenuItem(['Crud with Array Persistence'], [$path . 'crud3'], $menu);
    $body->addMenuItem('Card Deck', [$path . 'card-deck'], $menu);
    $body->addMenuItem(['Lister'], [$path . 'lister-ipp'], $menu);
    $body->addMenuItem(['Table column decorator from model'], [$path . 'tablecolumns'], $menu);

    $path = $demosUrl . 'data-action/';
    $menu = $body->addMenuGroup(['Data Action Executor', 'icon' => 'wrench']);
    $body->addMenuItem(['Executor Examples'], [$path . 'actions'], $menu);
    $body->addMenuItem(['Assign action to event'], [$path . 'jsactions'], $menu);
    $body->addMenuItem(['Assign action to button event'], [$path . 'jsactions2'], $menu);
    $body->addMenuItem(['Execute from Grid'], [$path . 'jsactionsgrid'], $menu);
    $body->addMenuItem(['Execute from Crud'], [$path . 'jsactionscrud'], $menu);
    $body->addMenuItem(['Executor Factory'], [$path . 'factory'], $menu);

    $path = $demosUrl . 'interactive/';
    $menu = $body->addMenuGroup(['Interactive', 'icon' => 'talk']);
    $body->addMenuItem('Tabs', [$path . 'tabs'], $menu);
    $body->addMenuItem('Card', [$path . 'card'], $menu);
    $body->addMenuItem(['Accordion'], [$path . 'accordion'], $menu);
    $body->addMenuItem(['Wizard'], [$path . 'wizard'], $menu);
    $body->addMenuItem(['Virtual Page'], [$path . 'virtual'], $menu);
    $body->addMenuItem('Modal', [$path . 'modal'], $menu);
    $body->addMenuItem(['Loader'], [$path . 'loader'], $menu);
    $body->addMenuItem(['Console'], [$path . 'console'], $menu);
    $body->addMenuItem(['Dynamic scroll'], [$path . 'scroll-lister'], $menu);
    $body->addMenuItem(['Background PHP Jobs (SSE)'], [$path . 'sse'], $menu);
    $body->addMenuItem(['Progress Bar'], [$path . 'progress'], $menu);
    $body->addMenuItem(['Pop-up'], [$path . 'popup'], $menu);
    $body->addMenuItem(['Toast'], [$path . 'toast'], $menu);
    $body->addMenuItem('Paginator', [$path . 'paginator'], $menu);
    $body->addMenuItem(['Drag n Drop sorting'], [$path . 'jssortable'], $menu);

    $path = $demosUrl . 'javascript/';
    $menu = $body->addMenuGroup(['Javascript', 'icon' => 'code']);
    $body->addMenuItem('Events', [$path . 'js'], $menu);
    $body->addMenuItem('Element Reloading', [$path . 'reloading'], $menu);
    $body->addMenuItem('Vue Integration', [$path . 'vue-component'], $menu);

    $path = $demosUrl . 'others/';
    $menu = $body->addMenuGroup(['Others', 'icon' => 'plus']);
    $body->addMenuItem('Sticky GET', [$path . 'sticky'], $menu);
    $body->addMenuItem('More Sticky', [$path . 'sticky2'], $menu);
    $body->addMenuItem('Recursive Views', [$path . 'recursive'], $menu);

    // view demo source page on Github
    \Phlex\Ui\Button::addTo($body->menu->addItem()->addClass('aligned right'), ['View Source', 'teal', 'icon' => 'github'])
        ->on('click', $webpage->jsRedirect('https://github.com/atk4/ui/blob/develop/' . $relUrl, true));
}
unset($body, $rootUrl, $relUrl, $demosUrl, $path, $menu);
