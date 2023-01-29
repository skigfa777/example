<?php

require 'conf.php';

//автоподгрузка классов
spl_autoload_register(function ($class) {
    $currentDir = PRFK_SITE_ROOT_DIR;
    
    // $class = str_replace('\\', '/', $class); //фикс для Unix-систем

    if (file_exists("$currentDir/inc/$class.php")) {
        include "inc/$class.php";
    }
});
