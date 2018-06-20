<?php

$files = array(
  '../interfaces/products_interface.php',
  '../interfaces/adminuser_interface.php',
  '../classes/products_class.php',
  '../classes/adminuser_class.php'
);

function IncludeFiles($files){
  for ($i=0; $i < count($files); $i++) {
    require_once '../'.$files[$i];
  }
}


?>
