<?php

namespace PHPDBSync\Core;

/**
 * For Loading the Classes in PHPDBSync
 *
 * @author      Raffael Wyss <raffael.wyss@gmail.com>
 * @since       19.09.2015
 * @version     1.00
 * @copyright   Copyright (c) 2015, Raffael Wyss
 * @package     PHPDBSync
 * @subpackage  Core
 */
class Loader
{

    public function autoload($className)
    {
        // Splitting the Class-Names
        $classFragments = explode('\\', $className);




    }

}