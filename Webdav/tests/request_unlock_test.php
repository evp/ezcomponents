<?php
/**
 * File containing the ezcWebdavUnlockRequestTest class.
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
 * Test case for the ezcWebdavUnlockRequest class.
 * 
 * @package Webdav
 * @subpackage Tests
 * @version 1.1.4
 */
class ezcWebdavUnlockRequestTest extends ezcWebdavRequestTestCase
{
    public static function suite()
    {
		return new PHPUnit_Framework_TestSuite( self::class );
    }

    protected function setUp()
    {
        $this->className = 'ezcWebdavUnlockRequest';
        $this->constructorArguments = ['/foo', '/bar'];
        $this->defaultValues = [];
        $this->workingValues = [];
        $this->failingValues = [];
    }

    public function testValidateHeadersSuccess()
    {
        $req = new ezcWebdavUnlockRequest( '/foo', '/bar' );
        $req->setHeader( 'Lock-Token', '<opaquelocktoken:a515cfa4-5da4-22e1-f5b5-00a0451e6bf7>' );
        $req->validateHeaders();
    }

    public function testValidateHeadersFailure()
    {
        $req = new ezcWebdavUnlockRequest( '/foo', '/bar' );
        
        try
        {
            $req->validateHeaders();
            $this->fail( 'Exception not thrown on missing Unlock-Token header.' );
        }
        catch ( ezcWebdavMissingHeaderException $e ) {}
    }
}

?>
