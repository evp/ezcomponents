<?php
/**
 * File containing the ezcWebdavPropPatchRequestTest class.
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
require_once 'request_test.php';

/**
 * Test case for the ezcWebdavPropPatchRequest class.
 * 
 * @package Webdav
 * @subpackage Tests
 * @version 1.1.4
 */
class ezcWebdavPropPatchRequestTest extends ezcWebdavRequestTestCase
{
    public static function suite()
    {
		return new PHPUnit_Framework_TestSuite( self::class );
    }

    protected function setUp()
    {
        $this->className = 'ezcWebdavPropPatchRequest';
        $this->constructorArguments = ['/foo', '/bar'];
        $this->defaultValues = ['updates' => new ezcWebdavFlaggedPropertyStorage()];
        $this->workingValues = ['updates' => [new ezcWebdavFlaggedPropertyStorage()]];
        $this->failingValues = ['updates' => [23, 23.34, 'foo bar', [23, 42], new stdClass()]];
    }
}

?>
