<?php

namespace PHPDBSync\Sync;
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
        Core::cliMessage(' > Load Source-Structur ', 'white', 1);
        $sourceTables= $this->getTableStructur($this->sourceDB);

        Core::cliMessage(' > Load Target-Structur ', 'white', 1);
        $targetTables= $this->getTableStructur($this->targetDB);

        echo print_r($sourceTables, 1);
        echo print_r($targetTables, 1);

    }

    /**
     * @param Database $database
     *
     * @return array
     */
    private function getTableStructur($database)
    {
        $tables = $database->query('SHOW FULL TABLES')->fetchAll();
        foreach ($tables AS &$table) {
            $table['name'] = $table['Tables_in_'.$database->name];
            Core::cliMessage('  > Table "'.$table['name'].'"', 'white', 1, false);
            $table['type'] = $table['Table_type'];
            $table['fields'] = $this->getColumnStructur(
                $database, $table['name']
            );
            unset($table['Tables_in_'.$database->name]);
            unset($table['Table_type']);
            Core::cliMessage(' done ', 'green', 1);
        }
        return $tables;
    }

    private function getColumnStructur($database, $tablename)
    {
        $columns = $database->query('SHOW FULL COLUMNS FROM '.$tablename)->fetchAll();
        return $columns;
    }

}