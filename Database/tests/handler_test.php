<?php
/**
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @version 1.4.7
 * @filesource
 * @package Database
 * @subpackage Tests
 */

/**
 * Test the handler classes.
 *
 * @package Database
 * @subpackage Tests
 */
class ezcDatabaseHandlerTest extends ezcTestCase
{
    protected function setUp()
    {
        try
        {
            $db = ezcDbInstance::get();
        }
        catch ( Exception $e )
        {
            $this->markTestSkipped();
        }
    }

    public function testConstructorNoDatabaseName()
    {
        try
        {
            // we'll create an instance of the correct type simply by making a similar one to the default.
            $db = ezcDbInstance::get();
            $className = get_class( $db );
            $db = new $className( [] );
            $this->fail( "Instantiating a handler with no database name should not be successful" );
        }
        catch ( ezcDbMissingParameterException $e ) {}
    }

    public function testIdentifierQuotingNoEscape()
    {
        $db = ezcDbInstance::get();
        switch ( get_class( $db ) )
        {
            case 'ezcDbHandlerMysql':
                $quoteChars = ['`', '`'];
                break;
            case 'ezcDbHandlerOracle':
            case 'ezcDbHandlerPgsql':
            case 'ezcDbHandlerSqlite':
                $quoteChars = ['"', '"'];
                break;
            case 'ezcDbHandlerMssql':
                $quoteChars = ['[', ']'];
                break;

            default:
                $this->markTestSkipped( "No quoting test defined for handler class '{" . get_class( $db ) . "}'" );
        }

        $this->assertEquals(
            $quoteChars[0] . 'TestIdentifier' . $quoteChars[1],
            $db->quoteIdentifier( 'TestIdentifier' )
        );
    }

    public function testIdentifierQuotingEscape()
    {
        $db = ezcDbInstance::get();
        switch ( get_class( $db ) )
        {
            case 'ezcDbHandlerMysql':
                $quoteChars = ['`', '`'];
                break;
            case 'ezcDbHandlerMssql':
                $db->setOptions( new ezcDbMssqlOptions( ['quoteIdentifier' => ezcDbMssqlOptions::QUOTES_COMPLIANT] ));
            case 'ezcDbHandlerOracle':
            case 'ezcDbHandlerPgsql':
            case 'ezcDbHandlerSqlite':
                $quoteChars = ['"', '"'];
                break;
            default:
                $this->markTestSkipped( "No quoting test defined for handler class '{" . get_class( $db ) . "}'" );
        }

        $this->assertEquals(
            $quoteChars[0] . "Test" . $quoteChars[1] . $quoteChars[1] . "Identifier" . $quoteChars[1],
            $db->quoteIdentifier( "Test" . $quoteChars[1] . "Identifier" )
        );
    }

    public function testMssqlIdentifierQuotingUntouched()
    {
        $db = ezcDbInstance::get();
        if ( get_class( $db ) != 'ezcDbHandlerMssql' ) 
        {
            $this->markTestSkipped( 'Test defined for MS SQL handler class only.' );
        }
        $db->setOptions( new ezcDbMssqlOptions( ['quoteIdentifier' => ezcDbMssqlOptions::QUOTES_UNTOUCHED] ));
        $quoteChars = ['"', '"'];
        $this->assertEquals( $quoteChars[0].'ezctesttable'.$quoteChars[1], $db->quoteIdentifier( 'ezctesttable' ));
    }

    public function testMssqlIdentifierQuotingCompliant()
    {
        $db = ezcDbInstance::get();
        if ( get_class( $db ) != 'ezcDbHandlerMssql' ) 
        {
            $this->markTestSkipped( 'Test defined for MS SQL handler class only.' );
        }
        $db->setOptions( new ezcDbMssqlOptions( ['quoteIdentifier' => ezcDbMssqlOptions::QUOTES_COMPLIANT] ));        

        $this->assertEquals( '"ezctesttable"', $db->quoteIdentifier( 'ezctesttable' ));
    }

    public function testMssqlIdentifierQuotingLegacy()
    {
        $db = ezcDbInstance::get();
        if ( get_class( $db ) != 'ezcDbHandlerMssql' ) 
        {
            $this->markTestSkipped( 'Test defined for MS SQL handler class only.' );
        }
        $db->setOptions( new ezcDbMssqlOptions( ['quoteIdentifier' => ezcDbMssqlOptions::QUOTES_LEGACY] ));

        $this->assertEquals( '[ezctesttable]', $db->quoteIdentifier( 'ezctesttable' ));
    }
        
    public function testMssqlIdentifierQuotingImpl()
    {
        $db = ezcDbInstance::get();
        if ( get_class( $db ) != 'ezcDbHandlerMssql' ) 
        {
            $this->markTestSkipped( 'Test defined for MS SQL handler class only.' );
        }
        $db->setOptions( new ezcDbMssqlOptions( ['quoteIdentifier' => ezcDbMssqlOptions::QUOTES_COMPLIANT] ));
        try {
            $db->query('CREATE TABLE '.$db->quoteIdentifier('group') . ' ( id INT )');
            $db->query('DROP TABLE '.$db->quoteIdentifier('group') );
        } 
        catch ( Exception $ex ) 
        {
            $this->fail( "Incorrect identifiers quoting ".$ex->getMessage() );
        }
    }

    public static function suite()
    {
         return new PHPUnit_Framework_TestSuite( "ezcDatabaseHandlerTest" );
    }
}

?>
