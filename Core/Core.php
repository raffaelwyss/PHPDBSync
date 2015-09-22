<?php

namespace PHPDBSync\Core;

/**
 * Base class for Storage global properties
 *
 * @author      Raffael Wyss <raffael.wyss@gmail.com>
 * @since       19.09.2015
 * @version     1.00
 * @copyright   Copyright (c) 2015, Raffael Wyss
 * @package     PHPDBSync
 * @subpackage  Core
 */
class Core
{

    /**
     * Is Commandline using
     *
     * @var boolean
     */
    public static $cli = false;

    /**
     * set the Detail-Level of the Messages
     *
     * @var integer 0 = Excpetions and Main-Messages
     *              1 = Show Every-Table-Sync-Message
     */
    public static $cliDetailLevel = 1;


    public static function cliMessage($message, $style = "white")
    {
        if (self::$cli) {
            switch ($style) {
                case 'red':
                    echo chr(27)."[0;31m";
                    break;
                case 'green':
                    echo chr(27)."[0;32m";
                    break;
                default:
                    echo chr(27)."[0;37m";
                    break;
            }
            echo " ".$message." \n";
        }
    }

}