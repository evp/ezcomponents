<?php
/**
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @filesource
 * @package Authentication
 * @version 1.3.1
 * @subpackage Tests
 */

include_once( 'Authentication/tests/test.php' );

/**
 * @package Authentication
 * @version 1.3.1
 * @subpackage Tests
 */
class ezcAuthenticationUrlTest extends ezcAuthenticationTest
{
    protected static $queriesParseQueryString = [
        // original URL, parse result, http_build_query() result
        ['', [], ''],
        ['foo', ['foo'    => null], 'foo='],
        ['foo=bar', ['foo'    => 'bar'], 'foo=bar'],
        ['foo[]=bar', ['foo'    => ['bar']], 'foo[0]=bar'],
        ['foo[][]=bar', ['foo'    => [['bar']]], 'foo[0][0]=bar'],
        ['foo[][][]=bar', ['foo'    => [[['bar']]]], 'foo[0][0][0]=bar'],
        ['foo[][]=bar&foo=baz', ['foo'    => 'baz'], 'foo=baz'],
        ['foo[][]=bar&foo[]=baz', ['foo'    => [['bar'], 'baz']], 'foo[0][0]=bar&foo[1]=baz'],
        ['foo[]=bar&foo[][]=baz', ['foo'    => ['bar', ['baz']]], 'foo[0]=bar&foo[1][0]=baz'],
        ['foo[][]=bar&foo[][]=baz', ['foo'    => [['bar'], ['baz']]], 'foo[0][0]=bar&foo[1][0]=baz'],
        ['foo=bar&answer=42', ['foo'    => 'bar', 'answer' => '42'], 'foo=bar&answer=42'],
        ['foo[]=bar&answer=42', ['foo'    => ['bar'], 'answer' => '42'], 'foo[0]=bar&answer=42'],
        ['foo[]=bar&answer=42&foo[]=baz', ['foo'    => ['bar', 'baz'], 'answer' => '42'], 'foo[0]=bar&foo[1]=baz&answer=42'],
        ['foo=bar&amp;answer=42', ['foo'    => 'bar', 'amp;answer' => '42'], 'foo=bar&amp;answer=42'],
        ['foo[0]=bar', ['foo'    => ['bar']], 'foo[0]=bar'],
        ['foo[1]=bar', ['foo'    => [1 => 'bar']], 'foo[1]=bar'],
        ['foo[0]=bar&foo[0]=baz', ['foo'    => ['baz']], 'foo[0]=baz'],
        ['foo[0][0]=bar&foo[0]=baz', ['foo'    => ['baz']], 'foo[0]=baz'],
        ['foo=ba+r', ['foo'    => 'ba r'], 'foo=ba r'],
        ['foo=ba%20r', ['foo'    => 'ba r'], 'foo=ba r'],
        ['foo=ba r', ['foo'    => 'ba r'], 'foo=ba r'],
        ['foo=ba.r', ['foo'    => 'ba.r'], 'foo=ba.r'],
        ['fo.o=bar', ['fo.o'   => 'bar'], 'fo.o=bar'],
        ['fo.o[]=bar', ['fo.o'   => ['bar']], 'fo.o[0]=bar'],
        ['fo:o=bar', ['fo:o'   => 'bar'], 'fo:o=bar'],
        ['fo;o=bar', ['fo;o'   => 'bar'], 'fo;o=bar'],
        ['foo()=bar', ['foo()'  => 'bar'], 'foo()=bar'],
        ['foo{}=bar', ['foo{}'  => 'bar'], 'foo{}=bar'],
        ['fo.o=bar&answer=42', ['fo.o'   => 'bar', 'answer' => 42], 'fo.o=bar&answer=42'],
        ['foo[=bar', ['foo_'   => 'bar'], 'foo_=bar'],
        ['foo[[=bar', ['foo_['  => 'bar'], 'foo_[=bar'],
        ['foo]=bar', ['foo]'   => 'bar'], 'foo]=bar'],
        ['foo]]=bar', ['foo]]'  => 'bar'], 'foo]]=bar'],
        ['foo][=bar', ['foo]_'  => 'bar'], 'foo]_=bar'],
        ['foo[[]=bar', ['foo'    => ['[' => 'bar']], 'foo[[]=bar'],
        ['foo][]=bar', ['foo]'   => ['bar']], 'foo][0]=bar'],
        ['foo[][=bar', ['foo'    => ['bar']], 'foo[0]=bar'],
        ['foo[]]=bar', ['foo'    => ['bar']], 'foo[0]=bar'],
        ['foo][[=bar', ['foo]_[' => 'bar'], 'foo]_[=bar'],
        ['fo[o=bar', ['fo_o'   => 'bar'], 'fo_o=bar'],
        ['fo[[o=bar', ['fo_[o'  => 'bar'], 'fo_[o=bar'],
        ['fo]o=bar', ['fo]o'   => 'bar'], 'fo]o=bar'],
        ['fo]]o=bar', ['fo]]o'  => 'bar'], 'fo]]o=bar'],
        ['fo][o=bar', ['fo]_o'  => 'bar'], 'fo]_o=bar'],
        ['foo[[]o=bar', ['foo'    => ['[' => 'bar']], 'foo[[]=bar'],
        ['foo][]o=bar', ['foo]'   => ['bar']], 'foo][0]=bar'],
        ['foo[][o=bar', ['foo'    => ['bar']], 'foo[0]=bar'],
        ['foo[]]o=bar', ['foo'    => ['bar']], 'foo[0]=bar'],
        ['fo[]o=bar', ['fo'     => ['bar']], 'fo[0]=bar'],
        ['fo][[o=bar', ['fo]_[o' => 'bar'], 'fo]_[o=bar'],
        ['foo[[0]o=bar', ['foo'    => ['[0' => 'bar']], 'foo[[0]=bar'],
        ['foo][0]o=bar', ['foo]'   => ['bar']], 'foo][0]=bar'],
        ['foo[0][o=bar', ['foo'    => ['bar']], 'foo[0]=bar'],
        ['foo[0]]o=bar', ['foo'    => ['bar']], 'foo[0]=bar'],
        ['fo[0]o=bar', ['fo'     => ['bar']], 'fo[0]=bar'],
    ];

    public static $url = "http://ezc.myopenid.com";
    public static $urlIncomplete = "ezc.myopenid.com";
    public static $urlNonexistent = "xxx";
    public static $urlWithPort = "http://www.google.com:80";
    public static $urlWithQuery = "http://www.myopenid.com/server?action=login";
    public static $urlNoHost = "/server";
    public static $urlEmpty = null;

    public static function suite()
    {
        return new PHPUnit_Framework_TestSuite( "ezcAuthenticationUrlTest" );
    }

    public function setUp()
    {

    }

    public function tearDown()
    {

    }

    public function testParseQueryString()
    {
        foreach ( self::$queriesParseQueryString as $query )
        {
            $params = ezcAuthenticationUrl::parseQueryString( $query[0] );

            $this->assertEquals( $query[1], $params, "Failed parsing '{$query[0]}'" );
            $this->assertEquals( $query[2], urldecode( http_build_query( $params ) ), "Failed building back the query '{$query[0]}' to '{$query[2]}'" );
        }
    }

    public function testOpenidUrlNormalizeUrl()
    {
        $url = self::$url;

        $result = ezcAuthenticationUrl::normalize( $url );
        $expected = 'http://ezc.myopenid.com';
        $this->assertEquals( $expected, $result );
    }

    public function testOpenidUrlNormalizeUrlIncomplete()
    {
        $url = self::$urlIncomplete;

        $result = ezcAuthenticationUrl::normalize( $url );
        $expected = 'http://ezc.myopenid.com';
        $this->assertEquals( $expected, $result );
    }

    public function testOpenidUrlNormalizeUrlNonexistent()
    {
        $url = self::$urlNonexistent;

        $result = ezcAuthenticationUrl::normalize( $url );
        $expected = 'http://xxx';
        $this->assertEquals( $expected, $result );
    }

    public function testUrlAppendQueryWithPort()
    {
        $url = self::$urlWithPort;

        $result = ezcAuthenticationUrl::appendQuery( $url, 'nonce', '123456' );
        $expected = 'http://www.google.com:80/?nonce=123456';
        $this->assertEquals( $expected, $result );
    }

    public function testUrlAppendQueryNoQuery()
    {
        $url = self::$url;

        $result = ezcAuthenticationUrl::appendQuery( $url, 'nonce', '123456' );
        $expected = 'http://ezc.myopenid.com/?nonce=123456';
        $this->assertEquals( $expected, $result );
    }

    public function testUrlAppendQueryExistingQuery()
    {
        $url = self::$urlWithQuery;

        $result = ezcAuthenticationUrl::appendQuery( $url, 'nonce', '123456' );
        $expected = 'http://www.myopenid.com/server?action=login&nonce=123456';
        $this->assertEquals( $expected, $result );
    }

    public function testUrlAppendQueryNoHost()
    {
        $url = self::$urlNoHost;

        $result = ezcAuthenticationUrl::appendQuery( $url, 'nonce', '123456' );
        $expected = '/server?nonce=123456';
        $this->assertEquals( $expected, $result );
    }

    public function testUrlAppendQueryEmpty()
    {
        $url = self::$urlEmpty;

        $result = ezcAuthenticationUrl::appendQuery( $url, 'nonce', '123456' );
        $expected = '?nonce=123456';
        $this->assertEquals( $expected, $result );
    }

    public function testUrlFetchQueryNoQuery()
    {
        $url = self::$url;

        $result = ezcAuthenticationUrl::fetchQuery( $url, 'nonce' );
        $expected = null;
        $this->assertEquals( $expected, $result );
    }

    public function testUrlFetchQueryWithQuery()
    {
        $url = self::$urlWithQuery;

        $result = ezcAuthenticationUrl::fetchQuery( $url, 'action' );
        $expected = 'login';
        $this->assertEquals( $expected, $result );
    }
}
?>
