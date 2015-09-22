<?php

namespace PHPDBSync\Sync;
use PDO;
use PHPDBSync\Core\Core;
use PHPDBSync\Core\Database;

/**
 * Sync the Structure of the Database
 *
 * @author      Raffael Wyss <raffael.wyss@gmail.com>
 * @since       19.09.2015
 * @version     1.00
 * @copyright   Copyright (c) 2015, Raffael Wyss
 * @package     PHPDBSync
 * @subpackage  Sync
 */
class Structur
{

    /**
     * @var null|Database
     */
    private $sourceDB = null;

    /**
     * @var null|Database
     */
    private $targetDB = null;

    /**
     * Constructor for the Structur-Class
     *
     * @param Database $sourceDB
     * @param Database $targetDB
     */
    public function __construct($sourceDB, $targetDB)
    {
        $this->sourceDB = $sourceDB;
        $this->targetDB = $targetDB;
    }

    public function synchronisation()
    {
        Core::cliMessage(' > Lade Source-Struktur ', 'white', 1);
        $sourceStructure = $this->getStructur($this->sourceDB);
        echo print_r($sourceStructure, 1);

    }

    /**
     * @param Database $database
     *
     * @return array
     */
    private function getStructur($database)
    {
        $tables = $database->query('SHOW FULL TABLES')->fetchAll();
        foreach ($tables AS &$table) {
            $table['name'] = $table['Tables_in_'.$database->name];
            $table['type'] = $table['Table_type'];
            $table['fields'] = array();
            unset($table['Tables_in_'.$database->name]);
            unset($table['Table_type']);
        }

        Core::cliMessage('  > Tabellen geladen', 'green', 1);



        $structure = array();


        return $structure;
    }

}