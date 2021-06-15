<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Ui\Header;

/** @var \Phlex\Ui\App $app */
require_once __DIR__ . '/../init-app.php';

Header::addTo($app, ['Actions in Crud', 'subHeader' => 'Crud will automatically setup Menu items based on actions defined in model.']);

// Actions can be added easily to the model

$files = new FileLock($app->db);

// This action must appear on top of the Crud
$action = $files->addUserAction(
    'import_from_filesystem',
    [
        'caption' => 'Import',
        'callback' => 'importFromFilesystem',
        'description' => 'Import file using path:',
        'preview' => function ($model, $path) {
            return 'Execute Import using path: "' . $path . '"';
        },
        'args' => [
            'path' => ['type' => 'string', 'required' => true],
        ],
        'appliesTo' => \Phlex\Data\Model\UserAction::APPLIES_TO_NO_RECORDS,
    ]
);

$files->addUserAction('download', function (\Phlex\Data\Model $model) {
    return 'File has been download!';
});

\Phlex\Ui\Crud::addTo($app, ['ipp' => 10])->setModel($files);
