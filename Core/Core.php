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
     * Is Echo using
     *
     * @var boolean
     */
    public static $echo = false;

    /**
     * set the Detail-Level of the Messages
     *
     * @var integer 0 = Excpetions and Main-Messages
     *              1 = Show Every-Table-Sync-Message
     */
    public static $detailLevel = 1;


    /**
     * Show the Messages
     *
     * @param string $message
     * @param string $style
     * @param integer $forLevel
     * @param boolean $newLine
     */
    public static function showMessage($message, $style = "white", $forLevel = 0, $newLine = true)
    {
        if (self::$cli AND $forLevel <= self::$detailLevel) {
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
            echo " ".$message;
            if ($newLine) {
                echo " \n";
            }
        } else if (self::$echo AND $forLevel <= self::$detailLevel) {
            switch ($style) {
                case 'red':
                    echo '<span style="color:#ff0000">';
                    break;
                case 'green':
                    echo '<span style="color:#00aa00">';
                    break;
                default:
                    echo '<span style="color:#000000">';
                    break;
            }
            echo " ".$message."<span>";
            if ($newLine) {
                echo " <br>";
            }
            ob_flush();
            flush();
        }
    }

}