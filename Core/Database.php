<?php

namespace PHPDBSync\Core;

use PDO;

/**
 * Base Class to etablish the Connection to the Database
 *
 * @author      Raffael Wyss <raffael.wyss@gmail.com>
 * @since       18.09.2015
 * @version     1.00
 * @copyright   Copyright (c) 2015, Raffael Wyss
 * @package     PHPDBSync
 * @subpackage  Core
 */
class Database
{
    /**
     * PDO Object for Call this object
     *
     * @var PDO
     */
    public $pdo;

    /**
     * Hold the Database-Name
     *
     * @var null|string
     */
    public $name = null;

    /**
     * Etablish the Connection to the Database
     *
     * @param string $engine    mysql is only supported
     * @param string $host      localhost
     * @param string $database  myDatabase
     * @param string $user      user1
     * @param string $password  password1
     *
     * @return boolean
     */
    public function connect($engine, $host, $database, $user, $password)
    {
        $return = false;
        $dsn = $engine.':host='.$host.';dbname='.$database;
        $this->pdo = new PDO($dsn, $user, $password, [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
        if ($this->pdo) {
            $this->name = $database;
            $return = true;
            $this->pdo->beginTransaction();
        }
        return $return;
    }

    public function query($sql, $data = array())
    {
        $statement = $this->pdo->prepare($sql);
        $statement->execute($data);
        if (!$statement->execute($data)) {
            Core::cliMessage('SQL-Fehler: '.$sql, 'red');
            $this->pdo->rollBack();
            return false;
        }
        return $statement;
    }

    /**
     * Close the database connection
     *
     * @return boolean
     */
    public function disconnect()
    {
        $this->pdo = null;
        return true;
    }
}