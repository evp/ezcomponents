<?php
/**
 * File containing the ezcWebdavPropFindRequestTest class.
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
 * Test case for the ezcWebdavPropFindRequest class.
 * 
 * @package Webdav
 * @subpackage Tests
 * @version 1.1.4
 */
class ezcWebdavPropFindRequestTest extends ezcWebdavRequestTestCase
{
    public static function suite()
    {
		return new PHPUnit_Framework_TestSuite( self::class );
    }

    protected function setUp()
    {
        $this->className = 'ezcWebdavPropFindRequest';
        $this->constructorArguments = ['/foo', '/bar'];
        $this->defaultValues = ['allProp'  => false, 'propName' => false, 'prop'     => null];
        $this->workingValues = ['allProp' => [true, false], 'propName' => [true, false], 'prop' => [new ezcWebdavBasicPropertyStorage(), null]];
        $this->failingValues = ['allProp' => [23, 23.34, 'foo bar', [23, 42], new stdClass()], 'propName' => [23, 23.34, 'foo bar', [23, 42], new stdClass()], 'prop' => [23, 23.34, 'foo bar', true, false, new stdClass(), [23, 42]]];
    }

    public function testValidateHeadersSuccess()
    {
        $req = new ezcWebdavPropFindRequest( '/foo', '/bar' );

        $req->setHeader( 'Depth', ezcWebdavPropFindRequest::DEPTH_ONE );
        $req->validateHeaders();

        $req->setHeader( 'Depth', ezcWebdavPropFindRequest::DEPTH_INFINITY );
        $req->validateHeaders();

        $req->setHeader( 'Depth', ezcWebdavPropFindRequest::DEPTH_ZERO );
        $req->validateHeaders();
    }

    public function testValidateHeadersFailure()
    {
        $req = new ezcWebdavPropFindRequest( '/foo', '/bar' );
        
        $req->setHeader( 'Depth', null );
        try
        {
            $req->validateHeaders();
            $this->fail( 'Exception not thrown on missing Depth header.' );
        }
        catch ( ezcWebdavMissingHeaderException $e ) {}

        $req->setHeader( 'Depth', 'A' );
        try
        {
            $req->validateHeaders();
            $this->fail( 'Exception not thrown on invalid Depth header.' );
        }
        catch ( ezcWebdavInvalidHeaderException $e ) {}
    }
}

?>
