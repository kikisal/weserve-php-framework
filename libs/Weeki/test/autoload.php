<?php


function pathRemoveFolder($path, $folder) {
    if (!str_contains($path, $folder))
        return $path;

    $entities = explode('/', $path);
    $result   = '';

    foreach ($entities as $e) {
        if ($e != $folder)
            $result .= $e . '/';
    }

    return substr($result, 0, strlen($result) - 1);
}

function resolveClassFile($class, $layer) {
    $classPath      = str_replace("\\", "/", $class);

    if ($layer > 0) 
        $classPath  = pathRemoveFolder($classPath, "Weeki");

    return $classPath . '.php';
}

spl_autoload_register(function($class) {
    
    $classFullPaths = [
        __DIR__ . '/tests/',
        __DIR__ . '/../src/'
    ];

    $layer = 0;
    foreach ($classFullPaths as $path) {

        $classFile = resolveClassFile($class, $layer++);
        $path = $path . $classFile;

        if (file_exists($path))
        {
            require $path;
            return true;
        }
    }

    return false;
});
