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
        Core::showMessage(' > Load Source-Structur ', 'white', 1);
        $sourceTables = $this->getTableStructur($this->sourceDB);

        echo '<pre>';
        print_r($sourceTables);

        Core::showMessage(' > Load Target-Structur ', 'white', 1);
        $targetTables= $this->getTableStructur($this->targetDB);
        $tTables = array();
        foreach ($targetTables AS $table) {
            array_push($tTables, $table['name']);
        }

        Core::showMessage(' > start synchronisation', 'white', 1);

        // Check Source-Tables...
        foreach ($sourceTables AS $sourceTable) {

            if (in_array($sourceTable['name'], $tTables)) {
                // Field-Check
                Core::showMessage('   > Table '.$sourceTable['name'].' ', 'white', 1, false);
                $alterStatements = $this->getAlterStatementsForTable(
                    $sourceTable,
                    $targetTables[array_search($sourceTable['name'], $tTables)]
                );
                if (count($alterStatements) > 0) {
                    $this->targetDB->query(implode(';', $alterStatements));
                    Core::showMessage(' updated ', 'green');
                } else {
                    Core::showMessage(' no changes ', 'green');
                }

            } else { // not exist
                // > CreateTable
                Core::showMessage('   > Table '.$sourceTable['name'].' ', 'white', 1, false);
                $createStatement = $this->getTableStatement(
                    $this->sourceDB, $sourceTable
                );
                $this->targetDB->query($createStatement);
                Core::showMessage(' created ', 'green');
            }

        }

    }

    /**
     * @param Database $database
     *
     * @return array
     */
    private function getTableStructur($database)
    {
        $tables = $database->query('SHOW TABLE STATUS')->fetchAll();
        $returnTables = array();
        foreach ($tables AS &$table) {
            Core::showMessage('  > Table "'.$table['Name'].'"', 'white', 1, false);
            $newTable = array();
            $newTable['name'] = $table['Name'];
            $newTable['engine'] = $table['Engine'];
            $newTable['collation'] = $table['Collation'];
            $newTable['fields'] = $this->getColumnStructur(
                $database, $newTable['name']
            );
            $newTable['indexes'] = $this->getIndexStructur(
                $database, $newTable['name']
            );
            Core::showMessage(' done ', 'green', 1);
            array_push($returnTables, $newTable);
        }
        return $returnTables;
    }

    private function getColumnStructur($database, $tablename)
    {
        $columns = $database->query('SHOW FULL COLUMNS FROM '.$tablename)->fetchAll();
        $newColumns = array();
        foreach ($columns AS $column) {
            $newColumns[$column['Field']] = $column;
        }
        return $newColumns;
    }

    private function getIndexStructur($database, $tablename)
    {
        $newIndexes = array();
        $indexes = $database->query('SHOW INDEX FROM '.$tablename)->fetchAll();
        foreach ($indexes AS $index) {
            $newIndexes[$index['Column_name']] = $index;
        }
        return $newIndexes;
    }

    /**
     * @param Database $database
     * @param String $table
     */
    private function getTableStatement($database, $table)
    {
        $data = $database->query(
            "SHOW CREATE TABLE ".$table['name']
        )->fetch();
        return $data['Create Table'];
    }

    private function getAlterStatementsForTable($sourceTable, $targetTable)
    {
        $returnStatement = array();


        foreach ($sourceTable['fields'] AS $fieldkey => $field) {

            if (isset($targetTable['fields'][$fieldkey])) {
                $statement = $this->getAlterStatementExistField(
                    $sourceTable['name'],
                    $field,
                    $targetTable['fields'][$fieldkey]
                );

            } else {
                $statement = $this->getAlterSatementNewField(
                    $sourceTable['name'], $field
                );
            }
            if ($statement != '') {
                array_push(
                    $returnStatement, $statement

                );
            }
        }

        return $returnStatement;
    }

    private function getAlterStatementExistField($sourceTable, $sourceField, $targetField)
    {
        $change = false;
        $alter = '';

        // Check-Type Change
        $type = $sourceField['Type'];
        if ($type != $targetField['Type']) {
            $change = true;
        }

        // Check-Null Change
        $null = " NULL ";
        if ($sourceField['Null'] == 'NO') {
            $null = " NOT NULL ";
        }
        if ($sourceField['Null'] != $targetField['Null']) {
            $change = true;
        }

        // Check-Default Change
        $default = "";
        if ($sourceField['Default'] != $targetField['Default']) {
            $change = true;
            if ($sourceField['Type'] == "timestamp") {
                $default = " DEFAULT ".$sourceField['Default'];
                $default .= " ".$sourceField['Extra'];
            } else {
                $default = " DEFAULT '".$sourceField['Default']."'";
            }
        }

        // Check-Extra-Change
        $extra = "";
        if (    $sourceField['Extra'] != $targetField['Extra']
            AND $sourceField['Type'] != 'timestamp') {
            $change = true;
            $extra = $sourceField['Extra'];
        }

        // Check Collation
        $collation = "";
        if ($sourceField['Collation'] != $targetField['Collation']) {
            $change = true;
            $collation = "CHARACTER SET utf8 COLLATE ".$sourceField['Collation'];
        }

        // Check Comment

        if ($change) {
            $alter .= 'ALTER TABLE '.$sourceTable;
            $alter .= ' MODIFY COLUMN '.$sourceField['Field'].' ';
            $alter .= ' '.$type.' '.$collation.' '.$null.' '.$default.' '.$extra;
        }

        return $alter;
    }

    private function getAlterSatementNewField($sourceTable, $sourceField)
    {
        $alter =  'ALTER TABLE '.$sourceTable;
        $alter .= ' ADD COLUMN '.$sourceField['Field'];
        $alter .= ' '.$sourceField['Type'];

        // Collation
        if ($sourceField['Collation'] != "") {
            $alter .= ' CHARACTER SET utf8 COLLATE '.$sourceField['Collation'];
        }

        // Null-Value
        if ($sourceField['Null'] == 'NO') {
            $alter .= ' NOT NULL';
        } else {
            $alter .= ' NULL';
        }

        // Default-Value
        if ($sourceField['Default'] != '') {
            if ($sourceField['Type'] == 'timestamp') {
                $alter .= ' DEFAULT '.$sourceField['Default'];
            } else {
                $alter .= ' DEFAULT "'.$sourceField['Default'];
            }
        }

        // Extra-Value
        if ($sourceField['Extra'] != '') {
            $alter .= ' '.$sourceField['Extra'];
        }

        return $alter;
    }

}