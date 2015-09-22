<?php

namespace PHPDBSync\Sync;
use PDO;

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
     * @var null|PDO
     */
    private $sourcePDO = null;

    /**
     * @var null|PDO
     */
    private $targetPDO = null;

    /**
     * Constructor for the Structur-Class
     *
     * @param PDO $sourcePDO
     * @param PDO $targetPDO
     */
    public function __construct($sourcePDO, $targetPDO)
    {
        $this->sourcePDO = $sourcePDO;
        $this->targetPDO = $targetPDO;
    }

    public function synchronisation()
    {
        $sourceStructure = $this->getStructur();
        echo print_r($sourceStructure, 1);

    }

    private function getStructur($typ = 'source')
    {
        $structure = array();


        return $structure;
    }

}