<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\Button;
use Phlex\Ui\Columns;
use Phlex\Ui\Header;
use Phlex\Ui\UserAction;
use Phlex\Ui\View;

/** @var \Phlex\Ui\Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

$files = new FileLock($webpage->db);

// Actions can be added easily to the model via the Model::addUserAction($name, $properties) method.
$action = $files->addUserAction(
    'import_from_filesystem',
    [
        // Which fields may be edited for the action. Default to all fields.
        // ModalExecutor for example, will only display fields set in this array.
        'fields' => [$files->key()->name],
        // callback function to call in model when action execute.
        // Can use a closure function or model method.
        'callback' => 'importFromFilesystem',
        // Some Ui action executor will use this property for displaying text in button.
        // Can be override by some Ui executor description property.
        'description' => 'Import file in a specify path.',
        // Display information prior to execute the action.
        // ModalExecutor or PreviewExecutor will display preview.
        'preview' => function ($model, $path) {
            return 'Execute Import using path: "' . $path . '"';
        },
        // Argument needed to run the callback action method.
        // Some ui executor will ask for arguments prior to run the action, like the ModalExecutor.
        'args' => [
            'path' => ['type' => 'string', 'required' => true],
        ],
        'appliesTo' => \Phlex\Data\Model\UserAction::APPLIES_TO_NO_RECORDS,
    ]
);

Header::addTo($webpage, [
    'Extentions to Phlex Data Actions',
    'subHeader' => 'Showing different UserAction executors that can execute Phlex\Data model action.',
]);

View::addTo($webpage, ['ui' => 'hidden divider']);

$columns = Columns::addTo($webpage, ['width' => 2]);
$rightColumn = $columns->addColumn();
$leftColumn = $columns->addColumn();

Header::addTo($rightColumn, [
    'JsCallbackExecutor',
    'subHeader' => 'Path argument is set via POST url when setting actions in executor.',
]);
// Explicitly adding an Action executor.
$executor = UserAction\JsCallbackExecutor::addTo($rightColumn);
// Passing Model action to executor and action argument via url.
$executor->setAction($action);
// Setting user response after model action get execute.
$executor->onHook(UserAction\BasicExecutor::HOOK_AFTER_EXECUTE, function ($t, $m) {
    return new \Phlex\Ui\JsToast('Files imported');
});
$executor->executeModelAction(['path' => '.']);

$btn = \Phlex\Ui\Button::addTo($rightColumn, ['Import File']);
$btn->on('click', $executor, ['confirm' => 'This will import a lot of file. Are you sure?']);

Header::addTo($rightColumn, ['BasicExecutor']);
$executor = UserAction\BasicExecutor::addTo($rightColumn, ['executorButton' => [Button::class, 'Import', 'primary']]);
$executor->setAction($action);
$executor->ui = 'segment';
$executor->description = 'Execute Import action using "BasicExecutor" with argument "path" equal to "."';
$executor->setArguments(['path' => '.']);
$executor->onHook(UserAction\BasicExecutor::HOOK_AFTER_EXECUTE, function ($x) {
    return new \Phlex\Ui\JsToast('Done!');
});

View::addTo($rightColumn, ['ui' => 'hidden divider']);

Header::addTo($rightColumn, ['PreviewExecutor']);
$executor = UserAction\PreviewExecutor::addTo($rightColumn, ['executorButton' => [Button::class, 'Confirm', 'primary']]);
$executor->setAction($action);
$executor->ui = 'segment';
$executor->previewType = 'console';
$executor->description = 'Displays preview in console prior to executing';
$executor->setArguments(['path' => '.']);
$executor->onHook(UserAction\BasicExecutor::HOOK_AFTER_EXECUTE, function ($x, $ret) {
    return new \Phlex\Ui\JsToast('Confirm!');
});

Header::addTo($leftColumn, ['FormExecutor']);
$executor = UserAction\FormExecutor::addTo($leftColumn, ['executorButton' => [Button::class, 'Save Name Only', 'primary']]);
$executor->setAction($action);
$executor->ui = 'segment';
$executor->description = 'Only fields set in $action[field] array will be added in form.';
$executor->setArguments(['path' => '.']);
$executor->onHook(UserAction\BasicExecutor::HOOK_AFTER_EXECUTE, function ($x, $ret) {
    return new \Phlex\Ui\JsToast('Confirm! ' . $x->action->getEntity()->name);
});

View::addTo($leftColumn, ['ui' => 'hidden divider']);

Header::addTo($leftColumn, ['ArgumentFormExecutor']);
$executor = UserAction\ArgumentFormExecutor::addTo($leftColumn, ['executorButton' => [Button::class, 'Run Import', 'primary']]);
$executor->setAction($action);
$executor->description = 'ArgumentFormExecutor will ask user about arguments set in actions.';
$executor->ui = 'segment';
$executor->onHook(UserAction\BasicExecutor::HOOK_AFTER_EXECUTE, function ($x, $ret) {
    return new \Phlex\Ui\JsToast('Imported!');
});
