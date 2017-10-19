<?php
/**
 * Created by PhpStorm.
 * Author: Adrian Dumitru
 * Date: 4/29/2017 7:51 PM
 */

spl_autoload_register(

/**
 * @param $class
 */
    function($class) {

        $nameSpace = str_replace('\\', '/', trim($class, '\\')) . '.php';
        $nameSpace = str_replace('Qpdb/QueryBuilder/', '', $nameSpace);
        $includeFile = __DIR__ . '/../src/' . $nameSpace;

        require_once($includeFile);

    }

);