<?php

use Illuminate\Support\Facades\File;

$moduleRoutesPath = app_path('Modules');

$modules = File::directories($moduleRoutesPath);

foreach ($modules as $modulePath) {
    $routeFile = $modulePath . '/Interfaces/Routes/' . strtolower(basename($modulePath)) . '.php';

    if (File::exists($routeFile)) {
        require $routeFile;
    }
}
