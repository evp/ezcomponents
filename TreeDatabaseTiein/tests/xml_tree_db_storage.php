<?php
/**
 * @copyright Copyright (C) 2005-2008 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @version 1.1.1
 * @filesource
 * @package TreeDatabaseTiein
 * @subpackage Tests
 */

/**
 * @package TreeDatabaseTiein
 * @subpackage Tests
 */
class ezcTreeXmlWithDbStorageTest extends ezcTestCase
{
    protected function setUp()
    {
        static $i = 0;

        $this->tempDir = $this->createTempDir( self::class . sprintf( '_%03d_', ++$i ) ) . '/';
        try
        {
            $this->dbh = ezcDbInstance::get();
            $this->removeTables();
            $this->loadSchemas();
        }
        catch ( Exception $e )
        {
            $this->markTestSkipped( $e->getMessage() );
        }
        $this->store = new ezcTreeDbExternalTableDataStore( $this->dbh, 'dataFrom', 'id', 'data' );
    }

    public function teardown()
    {
        $this->removeTempDir();
    }

    private function loadSchemas()
    {
        $schema = ezcDbSchema::createFromFile( 'array', __DIR__ . "/files/all-types.dba" );
        $schema->writeToDb( $this->dbh );
    }

    protected function removeTables()
    {
        try
        {
            foreach ( ['materialized_path', 'nested_set', 'parent_child', 'dataFrom', 'dataTo'] as $table )
            {
                $this->dbh->exec( "DROP TABLE $table" );
            }
        }
        catch ( Exception $e )
        {
            // ignore
        }
    }

    protected function addTestData( $tree )
    {
        $primates = ['Hominoidea' => ['Hylobatidae' => ['Hylobates' => ['Lar Gibbon', 'Agile Gibbon', 'Müller\'s Bornean Gibbon', 'Silvery Gibbon', 'Pileated Gibbon', 'Kloss\'s Gibbon'], 'Hoolock' => ['Western Hoolock Gibbon', 'Eastern Hoolock Gibbon'], 'Symphalangus' => [], 'Nomascus' => ['Black Crested Gibbon', 'Eastern Black Crested Gibbon', 'White-cheecked Crested Gibbon', 'Yellow-cheecked Gibbon']], 'Hominidae' => ['Pongo' => ['Bornean Orangutan', 'Sumatran Orangutan'], 'Gorilla' => ['Western Gorilla' => ['Western Lowland Gorilla', 'Cross River Gorilla'], 'Eastern Gorilla' => ['Mountain Gorilla', 'Eastern Lowland Gorilla']], 'Homo' => ['Homo Sapiens' => ['Homo Sapiens Sapiens', 'Homo Superior']], 'Pan' => ['Common Chimpanzee', 'Bonobo']]]];

        $root = $tree->createNode( 'Hominoidea', 'Hominoidea' );
        $tree->setRootNode( $root );

        $this->addChildren( $root, $primates['Hominoidea'] );
    }

    private function fixId( $id )
    {
        return preg_replace( '/[^A-Z0-9_]/i', '_', $id );
    }

    private function addChildren( ezcTreeNode $node, array $children )
    {
        foreach( $children as $name => $child )
        {
            if ( is_array( $child ) )
            {
                $newNode = $node->tree->createNode( $this->fixId( $name ), $name );
                $node->addChild( $newNode );
                $this->addChildren( $newNode, $child );
            }
            else
            {
                $newNode = $node->tree->createNode( $this->fixId( $child ), $child );
                $node->addChild( $newNode );
            }
        }
    }

    public function testTreeDbStorage()
    {
        $tree = ezcTreeXml::create( $this->tempDir . 'testDbStorage.xml', $this->store );
        $this->addTestData( $tree );
        self::assertSame( "Western Hoolock Gibbon", $tree->fetchNodeById( "Western_Hoolock_Gibbon" )->data );

        // start over
        $tree = new ezcTreeXml( $this->tempDir . 'testDbStorage.xml', $this->store );
        self::assertSame( "Western Hoolock Gibbon", $tree->fetchNodeById( "Western_Hoolock_Gibbon" )->data );
    }

    public static function suite()
    {
         return new PHPUnit_Framework_TestSuite( "ezcTreeXmlWithDbStorageTest" );
    }
}

?>
