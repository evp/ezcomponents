<?php
/**
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @version 1.4.7
 * @filesource
 * @package Database
 * @subpackage Tests
 */

require_once 'base.php';

/**
 * @package Database
 * @subpackage Tests
 */
class ezcDatabaseHandlerSqliteTest extends ezcDatabaseHandlerBaseTest
{
    public static function suite()
    {
         return new PHPUnit_Framework_TestSuite( self::class );
    }

    protected function setUp()
    {
        $this->handlerClass = 'ezcDbHandlerSqlite';
        parent::setUp();
    }

    protected function tearDown()
    {
        if ( $this->db === null ) return;

        $this->db->exec(
<<<EOT
    DELETE FROM sqlite_sequence;
EOT
        );
        parent::tearDown();
    }
}

?>
