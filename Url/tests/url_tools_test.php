<?php
/**
 * @copyright Copyright (C) 2005-2008 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @version 1.2.2
 * @filesource
 * @package Url
 * @subpackage Tests
 */

/**
 * @package Url
 * @subpackage Tests
 */
class ezcUrlToolsTest extends ezcTestCase
{
    protected static $queriesParseStr = [
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
        ['fo.o=bar', ['fo_o'   => 'bar'], 'fo_o=bar'],
        ['fo.o[]=bar', ['fo_o'   => ['bar']], 'fo_o[0]=bar'],
        ['fo_o=bar', ['fo_o'   => 'bar'], 'fo_o=bar'],
        ['f._o=bar', ['f__o'   => 'bar'], 'f__o=bar'],
        ['fo_o[]=bar', ['fo_o'   => ['bar']], 'fo_o[0]=bar'],
        ['fo:o=bar', ['fo:o'   => 'bar'], 'fo:o=bar'],
        ['fo;o=bar', ['fo;o'   => 'bar'], 'fo;o=bar'],
        ['foo()=bar', ['foo()'  => 'bar'], 'foo()=bar'],
        ['foo{}=bar', ['foo{}'  => 'bar'], 'foo{}=bar'],
        ['fo.o=bar&answer=42', ['fo_o'   => 'bar', 'answer' => 42], 'fo_o=bar&answer=42'],
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
        ['fo_o=bar', ['fo_o'   => 'bar'], 'fo_o=bar'],
        ['f._o=bar', ['f._o'   => 'bar'], 'f._o=bar'],
        ['fo_o[]=bar', ['fo_o'   => ['bar']], 'fo_o[0]=bar'],
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

    protected static $serverValues = [
        // HTTPS, SERVER_NAME, SERVER_PORT, REQUEST_URI, constructed URL
        [[null, 'www.example.com', 80, '/index.php', 'http://www.example.com/index.php']],
        [['1', 'www.example.com', 80, '/index.php', 'https://www.example.com/index.php']],
        [['on', 'www.example.com', 80, '/index.php', 'https://www.example.com/index.php']],
        [[null, 'www.example.com', 443, '/index.php', 'http://www.example.com:443/index.php']],
        [['1', 'www.example.com', 443, '/index.php', 'https://www.example.com:443/index.php']],
        [['on', 'www.example.com', 443, '/index.php', 'https://www.example.com:443/index.php']],
        [[null, 'www.example.com', 80, '', 'http://www.example.com']],
        [[null, 'www.example.com', 80, '/', 'http://www.example.com/']],
        [[null, 'www.example.com', 80, '/mydir/index.php', 'http://www.example.com/mydir/index.php']],
        [[null, 'www.example.com', 80, '/mydir/index.php/content', 'http://www.example.com/mydir/index.php/content']],
        [[null, 'www.example.com', 80, '/index.php?', 'http://www.example.com/index.php?']],
        [[null, 'www.example.com', 80, '/index.php?foo=bar', 'http://www.example.com/index.php?foo=bar']],
        [[null, 'www.example.com', 80, '/index.php?foo=bar#p1', 'http://www.example.com/index.php?foo=bar#p1']],
        [[null, null, null, null, 'http://']],
        [['on', null, null, null, 'https://']],
        [[null, 'www.example.com', null, null, 'http://www.example.com']],
        [[null, 'www.example.com', 81, null, 'http://www.example.com:81']],
        [[null, null, 81, null, 'http://:81']],
        [[null, null, 81, '/', 'http://:81/']],
        [[null, null, null, '/', 'http:///']],
        [[null, null, 80, '/', 'http:///']],
        [[true, null, 80, '/', 'http:///']],
    ];

    // the order of fields in self::$serverValues
    protected static $serverMapping = ['HTTPS', 'SERVER_NAME', 'SERVER_PORT', 'REQUEST_URI'];

    public static function suite()
    {
        return new PHPUnit_Framework_TestSuite( self::class );
    }

    public static function getQueriesParseStr()
    {
        return self::$queriesParseStr;
    }

    public static function getQueriesParseQueryString()
    {
        return self::$queriesParseQueryString;
    }

    public static function getServerValues()
    {
        return self::$serverValues;
    }

    /**
     * @dataProvider getQueriesParseStr
     */
    public function testParseStr( $query0, $query1, $query2 )
    {
        parse_str( $query0, $params );

        $this->assertEquals( $query1, $params, "Failed parsing '{$query0}'" );
        $this->assertEquals( $query2, urldecode( http_build_query( $params ) ), "Failed building back the query '{$query0}' to '{$query2}'" );
    }

    /**
     * @dataProvider getQueriesParseQueryString
     */
    public function testParseQueryString( $query0, $query1, $query2 )
    {
        $params = ezcUrlTools::parseQueryString( $query0 );

        $this->assertEquals( $query1, $params, "Failed parsing '{$query0}'" );
        $this->assertEquals( $query2, urldecode( http_build_query( $params ) ), "Failed building back the query '{$query0}' to '{$query2}'" );
    }

    /**
     * @dataProvider getServerValues
     */
    public function testGetCurrentUrlServer( $data )
    {
        $_SERVER = [];

        foreach ( self::$serverMapping as $key => $mapping )
        {
            if ( $data[$key] !== null )
            {
                $_SERVER[$mapping] = $data[$key];
            }
        }

        $expected = $data[4];

        $this->assertEquals( $expected, ezcUrlTools::getCurrentUrl(), "Failed building URL " . $data[4] );
    }

    /**
     * @dataProvider getServerValues
     */
    public function testGetCurrentUrlOtherSource( $data )
    {
        $source = [];

        foreach ( self::$serverMapping as $key => $mapping )
        {
            if ( $data[$key] !== null )
            {
                $source[$mapping] = $data[$key];
            }
        }

        $expected = $data[4];

        $this->assertEquals( $expected, ezcUrlTools::getCurrentUrl( $source ), "Failed building URL " . $data[4] );
    }
}
?>
