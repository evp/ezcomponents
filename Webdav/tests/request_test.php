<?php
/**
 * Class containing the ezcWebdavRequestTestCase class.
 *
 * @package Webdav
 * @subpackage Tests
 * @version 1.1.4
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Reqiuire base test
 */
require_once 'property_test.php';

/**
 * Base test case class for request class testing.
 * 
 * @package Webdav
 * @subpackage Tests
 * @version 1.1.4
 */
abstract class ezcWebdavRequestTestCase extends ezcWebdavPropertyTestCase
{
    /**
     * Array with constructor arguments.
     * 
     * @var array(mixed)
     */
    protected $constructorArguments = [];

	public static function suite()
	{
		return new PHPUnit_Framework_TestSuite( 'ezcWebdavRequestTestCase' );
	}

    /**
     * Get object of $className for testing.
     * 
     * @return stdClass
     */
    protected function getObject()
    {
        $class = new ReflectionClass( $this->className );
        return $class->newInstanceArgs( $this->constructorArguments );
    }
}
?>
