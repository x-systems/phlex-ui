<?php

declare(strict_types=1);
use Phlex\Ui\Webpage;

require_once __DIR__ . '/../vendor/autoload.php';

class GetAssets extends Webpage
{
    public $always_run = false;
    public $catch_exceptions = false;

    public function requireJs($path, $isAsync = false, $isDefer = false)
    {
        $file = 'public/' . basename($path);
        echo 'Downloading ' . $path . ' into ' . $file . '...' . "\n";
        if (@copy($path, $file)) {
            echo "  ok\n";
        } else {
            echo "  failed\n";
        }

        return $this;
    }

    public function requireCss($path)
    {
        return $this->requireJs($path);
    }
}

mkdir('public');
$app = new GetAssets();
