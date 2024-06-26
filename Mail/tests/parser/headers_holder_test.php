<?php
/**
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @version 1.7.1
 * @filesource
 * @package Mail
 * @subpackage Tests
 */

/**
 * @package Mail
 * @subpackage Tests
 */
class ezcMailHeadersHolderTest extends ezcTestCase
{
    public static function suite()
    {
        return new PHPUnit_Framework_TestSuite( "ezcMailHeadersHolderTest" );
    }

    public function testSet()
    {
        $reference = ['Subject' => 2, 'tO' => 3];
        $map = new ezcMailHeadersHolder();
        $map['Subject'] = 1;
        $map['suBject'] = 2;
        $map['tO'] = 3;
        $this->assertEquals( $reference, $map->getCaseSensitiveArray() );
    }

    public function testGet()
    {
        $map = new ezcMailHeadersHolder();
        $map['Subject'] = 1;
        $map['suBject'] = 2;
        $this->assertEquals( 2, $map['subject'] );
    }

    public function testGetEmpty()
    {
        $map = new ezcMailHeadersHolder();
        $this->assertEquals( null, $map['subject'] );
    }


    public function testUnset()
    {
        $reference = [];
        $map = new ezcMailHeadersHolder();
        $map['Subject'] = 1;
        $map['suBject'] = 2;
        unset( $map['subject'] );
        $this->assertEquals( $reference, $map->getCaseSensitiveArray() );
    }

    public function testKeyExists()
    {
        $reference = ['Subject' => 2, 'tO' => 3];
        $map = new ezcMailHeadersHolder();
        $map['Subject'] = 1;
        $this->assertEquals( false, isset( $map['Muha'] ) );
        $this->assertEquals( false, isset( $map['Muha'] ) ); // check that checking for not-set does not set it
        $this->assertEquals( true, isset( $map['subject'] ) );
    }
}

?>
