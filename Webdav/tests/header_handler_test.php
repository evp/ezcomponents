<?php
/**
 * Test case for the ezcWebdavHeaderHandler class.
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

/**
 * Tests for ezcWebdavHeaderHandler class.
 * 
 * @package Webdav
 * @subpackage Tests
 */
class ezcWebdavHeaderHandlerTest extends ezcTestCase
{
    private $oldServer;

    public static function suite()
    {
		return new PHPUnit_Framework_TestSuite( self::class );
    }

    public function setUp()
    {
        $this->oldServer = $_SERVER;
        $_SERVER         = [];
    }

    public function tearDown()
    {
        $_SERVER = $this->oldServer;
    }

    /**
     * testParseAuthorizationHeader 
     * 
     * @param mixed $headerContent 
     * @param mixed $expectedValue 
     * @return void
     *
     * @dataProvider provideAuthorizationHeaderSets
     */
    public function testParseAuthorizationHeader( $headerContent, $expectedValue )
    {
        $_SERVER['HTTP_AUTHORIZATION'] = $headerContent[0];
        if ( isset( $headerContent[1] ) )
        {
            $_SERVER['REQUEST_METHOD'] = $headerContent[1];
        }

        $headerHandler = new ezcWebdavHeaderHandler();
        $value = $headerHandler->parseHeader( 'Authorization' );

        $this->assertEquals(
            $expectedValue,
            $value,
            'Value for Authorization not parsed correctly.'
        );
    }

    /**
     * testParseAuthorizationHeaderDigestPreprocessed 
     * 
     * @param mixed $headerContent 
     * @param mixed $expectedValue 
     * @return void
     *
     * @dataProvider provideAuthorizationHeaderDigestPreprocessedSets
     */
    public function testParseAuthorizationHeaderDigestPreprocessed( $headerContent, $expectedValue )
    {
        $_SERVER['PHP_AUTH_DIGEST'] = $headerContent[0];
        if ( isset( $headerContent[1] ) )
        {
            $_SERVER['REQUEST_METHOD'] = $headerContent[1];
        }

        $headerHandler = new ezcWebdavHeaderHandler();
        $value = $headerHandler->parseHeader( 'Authorization' );

        $this->assertEquals(
            $expectedValue,
            $value,
            'Value for Authorization not parsed correctly.'
        );
    }

    /**
     * testParseAuthorizationHeaderBasicPreprocessed 
     * 
     * @param mixed $headerContent 
     * @param mixed $expectedValue 
     * @return void
     *
     * @dataProvider provideAuthorizationHeaderBasicPreprocessedSets
     */
    public function testParseAuthorizationHeaderBasicPreprocessed( $headerContent, $expectedValue )
    {
        $_SERVER['PHP_AUTH_USER'] = $headerContent[0];
        $_SERVER['PHP_AUTH_PW']   = $headerContent[1];

        $headerHandler = new ezcWebdavHeaderHandler();
        $value = $headerHandler->parseHeader( 'Authorization' );

        $this->assertEquals(
            $expectedValue,
            $value,
            'Value for Authorization not parsed correctly.'
        );
    }

    /**
     * testParseDepthHeader 
     * 
     * @param mixed $headerContent 
     * @param mixed $expectedValue 
     * @return void
     *
     * @dataProvider provideDepthTestSets
     */
    public function testParseDepthHeader( $headerContent, $expectedValue )
    {
        $_SERVER['HTTP_DEPTH'] = $headerContent;

        $headerHandler = new ezcWebdavHeaderHandler();
        $value = $headerHandler->parseHeader( 'Depth' );

        $this->assertEquals(
            $expectedValue,
            $value,
            'Value for Depth not parsed correctly.'
        );

    }

    /**
     * testParseIfMatchHeaderCorrect 
     * 
     * @param mixed $headerContent 
     * @param mixed $expectedValue 
     * @return void
     *
     * @dataProvider provideIfMatchTestSets
     */
    public function testParseIfMatchHeaderCorrect( $headerContent, $expectedValue )
    {
        $_SERVER['HTTP_IF_MATCH'] = $headerContent;

        $headerHandler = new ezcWebdavHeaderHandler();
        $value = $headerHandler->parseHeader( 'If-Match' );

        $this->assertEquals(
            $expectedValue,
            $value,
            'Value for If-Match-Header not parsed correctly.'
        );
    }

    /**
     * testParseIfNoneMatchHeaderCorrect 
     * 
     * @param mixed $headerContent 
     * @param mixed $expectedValue 
     * @return void
     *
     * @dataProvider provideIfMatchTestSets
     */
    public function testParseIfNoneMatchHeaderCorrect( $headerContent, $expectedValue )
    {
        $_SERVER['HTTP_IF_NONE_MATCH'] = $headerContent;

        $headerHandler = new ezcWebdavHeaderHandler();
        $value = $headerHandler->parseHeader( 'If-None-Match' );

        $this->assertEquals(
            $expectedValue,
            $value,
            'Value for If-None-Match-Header not parsed correctly.'
        );
    }

    public function testParseHeaderFailure()
    {
        $headerHandler = new ezcWebdavHeaderHandler();

        try
        {
            $headerHandler->parseHeader( 'Some Header' );
            $this->fail( 'Exception not thrown on request to parse unknown header.' );
        }
        catch ( ezcWebdavUnknownHeaderException $e ) {}
    }

    public function testParseHeaderNotAvailable()
    {
        $_SERVER = [];

        $headerHandler = new ezcWebdavHeaderHandler();
        
        $this->assertNull(
            $headerHandler->parseHeader( 'Depth' )
        );
    }

    public function testParseHeaderWithAlternatives()
    {
        $headerHandler = new ezcWebdavHeaderHandler();

        $_SERVER = ['HTTP_CONTENT_LENGTH' => 23];
        
        $this->assertEquals(
            23,
            $headerHandler->parseHeader( 'Content-Length' )
        );

        $_SERVER = ['CONTENT_LENGTH' => 23];
        
        $this->assertEquals(
            23,
            $headerHandler->parseHeader( 'Content-Length' )
        );

        $_SERVER = [];
        
        $this->assertNull(
            $headerHandler->parseHeader( 'Content-Length' )
        );
    }

    /**
     * testParseHeaders 
     * 
     * @param array $serverArr 
     * @param array $desiredHeaders 
     * @param array $expectedResult 
     * @return void
     *
     * @dataProvider provideParseHeadersTestSets
     */
    public function testParseHeaders( array $serverArr, array $desiredHeaders, array $expectedResult )
    {
        $_SERVER = $serverArr;
        
        $headerHandler = new ezcWebdavHeaderHandler();
        $result = $headerHandler->parseHeaders( $desiredHeaders );

        $this->assertEquals(
            $expectedResult,
            $result,
            'Headers not parsed correctly.'
        );
    }
    
    // Data providers

    public static function provideAuthorizationHeaderSets()
    {
        return [
            [['Basic Zm9vOmJhcg=='], new ezcWebdavBasicAuth( 'foo', 'bar' )],
            [['Basic     dXNlcjpwYXNz   '], new ezcWebdavBasicAuth( 'user', 'pass' )],
            [['Basic dXNlcjpwYXNzd2l0aDppbml0'], new ezcWebdavBasicAuth( 'user', 'passwith:init' )],
            // Simple digest, provided by Litmus
            [['Digest username="some", realm="eZ Components WebDAV", nonce="7feee2d8f6681389933bcdcbab789c8c", uri="/secure_collection/litmus/", response="ecde6f7d4bd072df1cb8f338f8a93132", algorithm="MD5"', 'GET'], new ezcWebdavDigestAuth(
                'GET',
                'some',
                'eZ Components WebDAV',
                '7feee2d8f6681389933bcdcbab789c8c',
                '/secure_collection/litmus/',
                'ecde6f7d4bd072df1cb8f338f8a93132',
                'MD5'
            )],
            // Complex digest, provided by WP
            [['Digest username="Mufasa", realm="testrealm@host.com", nonce="dcd98b7102dd2f0e8b11d0f600bfb0c093", uri="/dir/index.html", qop=auth, nc=00000001, cnonce="0a4f113b", response="6629fae49393a05397450978507c4ef1", opaque="5ccc069c403ebaf9f0171e9517f40e41"', 'PROPFIND'], new ezcWebdavDigestAuth(
                'PROPFIND',
                'Mufasa',
                'testrealm@host.com',
                'dcd98b7102dd2f0e8b11d0f600bfb0c093',
                '/dir/index.html',
                '6629fae49393a05397450978507c4ef1',
                'MD5',
                'auth',
                '00000001',
                '0a4f113b',
                '5ccc069c403ebaf9f0171e9517f40e41'
            )],
        ];
    }

    public static function provideAuthorizationHeaderBasicPreprocessedSets()
    {
        return [[['foo', 'bar'], new ezcWebdavBasicAuth( 'foo', 'bar' )], [['user', 'pass'], new ezcWebdavBasicAuth( 'user', 'pass' )], [['user', 'passwith:init'], new ezcWebdavBasicAuth( 'user', 'passwith:init' )]];
    }

    public static function provideAuthorizationHeaderDigestPreprocessedSets()
    {
        return [
            // Simple digest, provided by Litmus
            [['username="some", realm="eZ Components WebDAV", nonce="7feee2d8f6681389933bcdcbab789c8c", uri="/secure_collection/litmus/", response="ecde6f7d4bd072df1cb8f338f8a93132", algorithm="MD5"', 'GET'], new ezcWebdavDigestAuth(
                'GET',
                'some',
                'eZ Components WebDAV',
                '7feee2d8f6681389933bcdcbab789c8c',
                '/secure_collection/litmus/',
                'ecde6f7d4bd072df1cb8f338f8a93132',
                'MD5'
            )],
            // Complex digest, provided by WP
            [['username="Mufasa", realm="testrealm@host.com", nonce="dcd98b7102dd2f0e8b11d0f600bfb0c093", uri="/dir/index.html", qop=auth, nc=00000001, cnonce="0a4f113b", response="6629fae49393a05397450978507c4ef1", opaque="5ccc069c403ebaf9f0171e9517f40e41"', 'PROPFIND'], new ezcWebdavDigestAuth(
                'PROPFIND',
                'Mufasa',
                'testrealm@host.com',
                'dcd98b7102dd2f0e8b11d0f600bfb0c093',
                '/dir/index.html',
                '6629fae49393a05397450978507c4ef1',
                'MD5',
                'auth',
                '00000001',
                '0a4f113b',
                '5ccc069c403ebaf9f0171e9517f40e41'
            )],
        ];
    }

    public static function provideDepthTestSets()
    {
        return [['0', ezcWebdavRequest::DEPTH_ZERO], ['1', ezcWebdavRequest::DEPTH_ONE], ['infinity', ezcWebdavRequest::DEPTH_INFINITY], ['  0    ', ezcWebdavRequest::DEPTH_ZERO], [' 1   ', ezcWebdavRequest::DEPTH_ONE], [' infinity     ', ezcWebdavRequest::DEPTH_INFINITY], ['some misc test', 'some misc test']];
    }

    public static function provideIfMatchTestSets()
    {
        return [['"Simple tag"', ['Simple tag']], ['"one tag", "another tag", "a third tag"', ["one tag", "another tag", "a third tag"]], ['"abc", "xyz"', ['abc', 'xyz']], ['"with empty", "", "tag"', ['with empty', '', 'tag']], ['  "with additional"  ,  "characters", ..  "in it"', ['with additional', 'characters', 'in it']], ['*', true], ['  * ', true]];
    }

    public static function provideParseHeadersTestSets()
    {
        return [
            // Set 1
            [['HTTP_DEPTH' => 0], ['Depth'], ['Depth' => ezcWebdavRequest::DEPTH_ZERO]],
            // Set 2
            [['HTTP_DEPTH' => 0], ['Depth', 'Content-Type'], ['Depth' => ezcWebdavRequest::DEPTH_ZERO]],
            // Set 3
            [['HTTP_DEPTH'      => 1, 'CONTENT_TYPE'    => 'text/plain; charset=utf-8', 'HTTP_LOCK_TOKEN' => 'abc'], ['Depth'], ['Depth' => ezcWebdavRequest::DEPTH_ONE]],
            // Set 4
            [['HTTP_DEPTH'      => 1, 'CONTENT_TYPE'    => 'text/plain; charset=utf-8', 'HTTP_LOCK_TOKEN' => 'abc'], ['Depth', 'Content-Type'], ['Depth'        => ezcWebdavRequest::DEPTH_ONE, 'Content-Type' => 'text/plain; charset=utf-8']],
            // Set 4
            [['HTTP_DEPTH'      => 1, 'CONTENT_TYPE'    => 'text/plain; charset=utf-8', 'HTTP_LOCK_TOKEN' => 'abc', 'HTTP_IF_MATCH'   => '"foo", "bar", "baz"'], ['Depth', 'Content-Type'], ['Depth'        => ezcWebdavRequest::DEPTH_ONE, 'Content-Type' => 'text/plain; charset=utf-8', 'If-Match'     => ['foo', 'bar', 'baz']]],
            // Set 5
            [['HTTP_DEPTH'      => 1, 'CONTENT_TYPE'    => 'text/plain; charset=utf-8', 'HTTP_LOCK_TOKEN' => 'abc', 'HTTP_IF_MATCH'   => '"foo", "bar", "baz"', 'HTTP_IF_NONE_MATCH'   => '"bar"'], ['Depth', 'Content-Type'], ['Depth'        => ezcWebdavRequest::DEPTH_ONE, 'Content-Type' => 'text/plain; charset=utf-8']],
            // Set 6
            [['HTTP_AUTHORIZATION' => 'Basic Zm9vOmJhcg==', 'HTTP_DEPTH'         => 1], ['Depth', 'Authorization'], ['Depth'         => ezcWebdavRequest::DEPTH_ONE, 'Authorization' => new ezcWebdavBasicAuth( 'foo', 'bar' )]],
            // Set 7
            [['PHP_AUTH_USER' => 'foo', 'PHP_AUTH_PW'   => 'bar', 'HTTP_DEPTH'    => 1], ['Depth', 'Authorization'], ['Depth'         => ezcWebdavRequest::DEPTH_ONE, 'Authorization' => new ezcWebdavBasicAuth( 'foo', 'bar' )]],
            // Set 8
            [['HTTP_AUTHORIZATION' => 'Digest username="some", realm="eZ Components WebDAV", nonce="7feee2d8f6681389933bcdcbab789c8c", uri="/secure_collection/litmus/", response="ecde6f7d4bd072df1cb8f338f8a93132", algorithm="MD5"', 'REQUEST_METHOD'     => 'GET', 'HTTP_DEPTH'         => 1], ['Depth', 'Authorization'], ['Depth'         => ezcWebdavRequest::DEPTH_ONE, 'Authorization' => new ezcWebdavDigestAuth(
                'GET',
                'some',
                'eZ Components WebDAV',
                '7feee2d8f6681389933bcdcbab789c8c',
                '/secure_collection/litmus/',
                'ecde6f7d4bd072df1cb8f338f8a93132',
                'MD5'
            )]],
            // Set 9
            [['PHP_AUTH_DIGEST' => 'username="some", realm="eZ Components WebDAV", nonce="7feee2d8f6681389933bcdcbab789c8c", uri="/secure_collection/litmus/", response="ecde6f7d4bd072df1cb8f338f8a93132", algorithm="MD5"', 'REQUEST_METHOD'  => 'GET', 'HTTP_DEPTH'      => 1], ['Depth', 'Authorization'], ['Depth'         => ezcWebdavRequest::DEPTH_ONE, 'Authorization' => new ezcWebdavDigestAuth(
                'GET',
                'some',
                'eZ Components WebDAV',
                '7feee2d8f6681389933bcdcbab789c8c',
                '/secure_collection/litmus/',
                'ecde6f7d4bd072df1cb8f338f8a93132',
                'MD5'
            )]],
        ];
    }
}


?>
