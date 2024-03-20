<?php
/**
 * ezcImageSaveOptionsTest
 *
 * @package ImageConversion
 * @version 1.3.8
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

class ezcImageConversionSaveOptionsTest extends ezcTestCase
{

	public static function suite()
	{
		return new PHPUnit_Framework_TestSuite( self::class );
	}

    public function testGetAccessSuccess()
    {
        $opt = new ezcImageSaveOptions();

        $this->assertNull( $opt->compression );
        $this->assertNull( $opt->quality );
        $this->assertNull( $opt->transparencyReplacementColor );
    }

    public function testGetAccessFailure()
    {
        $opt = new ezcImageSaveOptions();
        
        try
        {
            echo $opt->foo;
        }
        catch ( ezcBasePropertyNotFoundException $e )
        {
            return;
        }
        $this->fail( "ezcBasePropertyNotFoundException not thrown on get access to invalid property foo." );
    }

    public function testSetAccessSuccess()
    {
        $opt = new ezcImageSaveOptions();

        $this->assertSetProperty(
            $opt,
            'compression',
            range( 0, 9, 1 )
        );
        $this->assertSetProperty(
            $opt,
            'quality',
            range( 0, 100, 10 )
        );
        $this->assertSetProperty(
            $opt,
            'transparencyReplacementColor',
            [[23, 42, 13], [0, 0, 0]]
        );
    }

    public function testSetAccessFailure()
    {
        $opt = new ezcImageSaveOptions();

        $this->assertSetPropertyFails(
            $opt,
            'compression',
            [true, false, 23.42, 'foo', [], new stdClass(), -1, 10, -23]
        );
        $this->assertSetPropertyFails(
            $opt,
            'quality',
            [true, false, 23.42, 'foo', [], new stdClass(), -1, 101, -23]
        );
        $this->assertSetPropertyFails(
            $opt,
            'transparencyReplacementColor',
            [true, false, 23.42, 'foo', [], new stdClass(), -1, 101, [42, 23], ['foo' => 42, 'bar' => 23], [1 => 0, 2 => 0, 3 => 0], ['foo' => 'bar']]
        );

        try
        {
            $opt->foo = 23;
        }
        catch ( ezcBasePropertyNotFoundException $e )
        {
            return;
        }
        $this->fail( "ezcBasePropertyNotFoundException not thrown on set access to invalid property foo." );
    }
}

?>
