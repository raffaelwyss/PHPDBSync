<?php

namespace PHPDBSync;
use PHPDBSync\Core\Core;
use PHPDBSync\Core\Database;
use PHPDBSync\Core\Loader;
use \PDO;
use PHPDBSync\Sync\Structur;

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
     * @var null|Database
     */
    private $sourceDB = null;

    /**
     * @var null|Database
     */
    private $targetDB = null;

    /**
     * Load the Base Information and set the autoloader
     */
    public function __construct()
    {
        $this->basePath = realpath(__DIR__);

        // start autoloader
        $loaderPath = $this->basePath.'/Core/Loader.php';
        require_once $loaderPath;
        $loader = new Loader($this->basePath);
        spl_autoload_register([$loader, 'autoload']);

        // Exception-Handler
        set_exception_handler(
            ['PHPDBSync\\Core\\ExceptionHandler', 'handleException']
        );
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

    /**
     * set the showMessage-Parameter
     *
     * @param string $type
     *                  cli = command line
     *                  echo = php-echo
     */
    public function setShowMessages($type = "")
    {
        Core::$cli = false;
        Core::$echo = false;
        if ($type == 'cli') {
            Core::$cli = true;
        } else if ($type == 'echo') {
            Core::$echo = true;
        }
    }

    public function synchronisation($sourceName, $targetName)
    {
        // Show the Start-Message
        Core::showMessage("Synchronisation started");

        // Connect-Databases
        if ($this->connectDatabases($sourceName, $targetName)) {

            // Structur Sync
            $_structur = new Structur($this->sourceDB, $this->targetDB);
            $_structur->synchronisation();


            // Full-Tables Sync

            // Content-Tables Sync

        }

        /**
         * Show the Finish-Message
         */
        Core::showMessage("Synchronisation finished");
    }


    private function connectDatabases($sourceName, $targetName)
    {
        // Connection to the Source-Database
        $sourceDB = new Database();
        $sourceConfig = $this->storageDatabases[$sourceName];
        $sourceDB->connect(
            $sourceConfig['engine'],
            $sourceConfig['host'],
            $sourceConfig['name'],
            $sourceConfig['user'],
            $sourceConfig['password']
        );
        $this->sourceDB = $sourceDB;
        Core::showMessage(" > SourceDB connected", 'green');

        // Connection to the Target-Database
        $targetDB = new Database();
        $targetConfig = $this->storageDatabases[$targetName];
        $targetDB->connect(
            $targetConfig['engine'],
            $targetConfig['host'],
            $targetConfig['name'],
            $targetConfig['user'],
            $targetConfig['password']
        );
        $this->targetDB = $targetDB;
        Core::showMessage(" > TargetDB connected", 'green');

        if ($this->sourceDB->pdo !== false AND $this->targetDB->pdo !== false) {
            return true;
        }
        return false;
    }


}