<?php
/**
 * File containing the ezcBaseFileException class
 *
 * @package Base
 * @version 1.8
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */
/**
 * ezcBaseFileException is the exception from which all file related exceptions
 * inherit.
 *
 * @package Base
 * @version 1.8
 */
abstract class ezcBaseFileException extends ezcBaseException
{
    public const READ    = 1;
    public const WRITE   = 2;
    public const EXECUTE = 4;
    public const CHANGE  = 8;
    public const REMOVE  = 16;
}
?>
