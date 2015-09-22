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
    /**
     * Path to the base Root from PHPDBSync
     *
     * @var string
     */
    private $basePath = '';

    public function __construct($basePath)
    {
        $this->basePath = $basePath;
    }

    public function autoload($className)
    {
        // Splitting the Class-Names
        $classFragments = explode('\\', $className);

        // Check if the Class from PHPDBSync
        if (   count($classFragments) == 0
            OR $classFragments[0] != 'PHPDBSync') {
            return false;
        }

        // Load the File
        $filePath = $this->basePath.'/'.
            implode("/", array_slice($classFragments, 1)).'.php';
        if (!file_exists($filePath)) {
            return false;
        }
        require_once $filePath;

    }

}