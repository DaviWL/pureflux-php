<?php
namespace pureflux\core;

spl_autoload_register(function ($class)
{
    $classClean = str_replace("\\","/",$class);
    $className = WEBROOT.DS.$classClean.".php";
    
    if (file_exists($className)) {
        require_once $className;
    } else {
        throw new \Exception($className." don't found.");
    }
});

//   "/{{.*}}/i"