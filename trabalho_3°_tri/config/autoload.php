<?php
    require_once __DIR__ . "/config.php";
    
    spl_autoload_register(function ($classe) {
        
        if(str_contains($classe, "Controller")){
            require_once __DIR__ . "/../src/MVC/Controller/$classe.php";
        }
        else{
            require_once __DIR__ . "/../src/MVC/Model/$classe.php";
        }
    });
    
?>