<?php

/**
 * Autoloader for the App namespace
 * 
 * Automatically loads classes without need for includes
 * 
 * @author Louis Figes
 * @generated Github CoPilot was used during the creation of this code
 * 
*/
function autoloaderRegister() {
    spl_autoload_register('autoloader');
}

function autoloader($className) {
    $filename = $className . ".php";
    $filename = str_replace('\\', DIRECTORY_SEPARATOR, $filename);
    $filename = $_SERVER['DOCUMENT_ROOT'] .'/api/' . $filename;
    if (is_readable($filename)) {
        include_once $filename;
    } else {
        throw new Exception("File '$filename' not found");
    }
}