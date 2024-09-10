<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Data\Model;
use Phlex\Data\Model\UserAction;
use Phlex\Ui\Crud;
use Phlex\Ui\Header;
use Phlex\Ui\Webpage;

/** @var Webpage $webpage */
require_once __DIR__ . '/../init-app.php';

Header::addTo($webpage, ['Actions in Crud', 'subHeader' => 'Crud will automatically setup Menu items based on actions defined in model.']);

// Actions can be added easily to the model

$files = new FileLock($webpage->db);

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
        'appliesTo' => UserAction::APPLIES_TO_NO_RECORDS,
    ]
);

$files->addUserAction('download', function (Model $model) {
    return 'File has been download!';
});

Crud::addTo($webpage, ['ipp' => 10])->setModel($files);
