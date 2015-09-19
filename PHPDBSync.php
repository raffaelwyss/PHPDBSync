<?php

namespace PHPDBSync;
use PHPDBSync\Core\Database;
use PHPDBSync\Core\Loader;

/**
 * Sync-Controller
 *
 * @author      Raffael Wyss <raffael.wyss@gmail.com>
 * @since       19.09.2015
 * @version     1.00
 * @copyright   Copyright (c) 2015, Raffael Wyss
 * @package     PHPDBSync
 */
class PHPDBSync
{
    /**
     * Path to the base Root from PHPDBSync
     *
     * @var string
     */
    private $basePath = '';

    /**
     * Storage for the Database-Configurations
     *
     * @var array
     */
    private $storageDatabases = array();

    /**
     * Load the Base Information and set the autoloader
     */
    public function __construct()
    {
        $this->basePath = realpath(__DIR__);

        // start autoloader
        $loaderPath = $this->basePath.'/Core/Loader.php';
        require_once $loaderPath;
        $loader = new Loader();
        spl_autoload_register([$loader, 'autoload']);
    }

    /**
     * Save the Config in the Storage
     *
     * @param string $storageName
     * @param string $engine
     * @param string $host
     * @param string $name
     * @param string $user
     * @param string $password
     *
     * @return array
     */
    public function setDB($storageName, $engine, $host, $name, $user, $password)
    {
        $database = array();
        $database['engine'] = $engine;
        $database['host'] = $host;
        $database['name'] = $name;
        $database['user'] = $user;
        $database['password'] = $password;
        $this->storageDatabases[$storageName] = $database;
        return $this->storageDatabases[$storageName];
    }

    public function synchronisation($sourceName, $targetName)
    {
        $sourceConfig = $this->storageDatabases[$sourceName];
        $sourceDB = new Database();
        $sourcePDO = $sourceDB->connect(
            $sourceConfig['engine'],
            $sourceConfig['host'],
            $sourceConfig['name'],
            $sourceConfig['user'],
            $sourceConfig['password']
        );



        $targetDB = new Database();


    }


}