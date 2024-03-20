<?php
/**
 * Basic test cases for the response class.
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
 * Tests for ezcWebdavBasicPathFactory class.
 * 
 * @package Webdav
 * @subpackage Tests
 */
class ezcWebdavResponseTest extends ezcWebdavRequestTestCase
{
    public static function suite()
    {
		return new PHPUnit_Framework_TestSuite( self::class );
    }

    protected function setUp()
    {
        $this->className = 'ezcWebdavMultistatusResponse';
        $this->defaultValues = ['responseDescription' => null];
        $this->workingValues = ['responseDescription' => ['This is nice response!']];
        $this->failingValues = ['responseDescription' => [42, true]];
    }

    public function testMultistatusResponseSingle()
    {
        $response = new ezcWebdavMultistatusResponse(
            $error = new ezcWebdavErrorResponse( ezcWebdavResponse::STATUS_404 )
        );

        $this->assertEquals(
            $response->responses,
            [$error],
            'Expected array with one response.'
        );
    }

    public function testMultistatusResponseMultiple()
    {
        $response = new ezcWebdavMultistatusResponse(
            $error1 = new ezcWebdavErrorResponse( ezcWebdavResponse::STATUS_404 ),
            $error2 = new ezcWebdavErrorResponse( ezcWebdavResponse::STATUS_409 )
        );

        $this->assertEquals(
            $response->responses,
            [$error1, $error2],
            'Expected array with one response.'
        );
    }

    public function testMultistatusResponseMultipleFlatten()
    {
        $response = new ezcWebdavMultistatusResponse(
            $error1 = new ezcWebdavErrorResponse( ezcWebdavResponse::STATUS_404 ),
            [$error2 = new ezcWebdavErrorResponse( ezcWebdavResponse::STATUS_409 )]
        );

        $this->assertEquals(
            $response->responses,
            [$error1, $error2],
            'Expected array with one response.'
        );
    }

    public function testMultistatusResponseMultipleOnlyFlatten()
    {
        $response = new ezcWebdavMultistatusResponse(
            [$error1 = new ezcWebdavErrorResponse( ezcWebdavResponse::STATUS_404 ), $error2 = new ezcWebdavErrorResponse( ezcWebdavResponse::STATUS_409 )]
        );

        $this->assertEquals(
            $response->responses,
            [$error1, $error2],
            'Expected array with one response.'
        );
    }
}

?>
