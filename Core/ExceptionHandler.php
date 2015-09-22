<?php
namespace PHPDBSync\Core;
use Exception;

/**
 * Beschreibung
 *
 * @author      Raffael Wyss <raffael.wyss@gmail.com>
 * @since       22.09.2015
 * @version     1.00
 * @copyright   Copyright (c) 2015, Raffael Wyss
 * @package     PHPDBSync
 * @subpackage  Core
 */
class ExceptionHandler extends Exception
{
    public static function handleException(Exception $exception)
    {
        if (Core::$cli) {
            Core::cliMessage($exception->getMessage(), 'red');
            Core::cliMessage(
                $exception->getLine() .' in file '.$exception->getFile(), 'red'
            );
        }
    }
}