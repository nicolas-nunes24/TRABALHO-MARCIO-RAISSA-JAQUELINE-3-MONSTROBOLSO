<?php
<<<<<<< HEAD
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
=======

require_once __DIR__ . "/config.php";

spl_autoload_register(function ($classe) {
  
    if (str_contains($classe, "Controller")) {
        $arquivo = __DIR__ . "/../src/MVC/Controller/{$classe}.php";
    } else {
        $arquivo = __DIR__ . "/../src/MVC/Model/{$classe}.php";
    }

 
    if (file_exists($arquivo)) {
        require_once $arquivo;
    }
});
>>>>>>> 79f7883b41261b29acac70dd18f86864678bb739
