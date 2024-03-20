<?php
/**
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @version 1.1.4
 * @filesource
 * @package Tree
 * @subpackage Tests
 */

require_once 'tree.php';

/**
 * @package Tree
 * @subpackage Tests
 */
class ezcTreeVisitorTest extends ezcTestCase
{
    public function setUp()
    {
        $this->tree = ezcTreeMemory::create( new ezcTreeMemoryDataStore() );
    }

    protected function addTestData( $tree )
    {
        $primates = ['Hominoidea' => ['Hylobatidae' => ['Hylobates' => ['Lar Gibbon', 'Agile Gibbon', 'Müller\'s Bornean Gibbon', 'Silvery Gibbon', 'Pileated Gibbon', 'Kloss\'s Gibbon'], 'Hoolock' => ['Western Hoolock Gibbon', 'Eastern Hoolock Gibbon'], 'Symphalangus' => [], 'Nomascus' => ['Black Crested Gibbon', 'Eastern Black Crested Gibbon', 'White-cheecked Crested Gibbon', 'Yellow-cheecked Gibbon']], 'Hominidae' => ['Pongo' => ['Bornean Orangutan', 'Sumatran Orangutan'], 'Gorilla' => ['Western Gorilla' => ['Western Lowland Gorilla', 'Cross River Gorilla'], 'Eastern Gorilla' => ['Mountain Gorilla', 'Eastern Lowland Gorilla']], 'Homo' => ['Homo Sapiens' => ['Homo Sapiens Sapiens', 'Homo Superior']], 'Pan' => ['Common Chimpanzee', 'Bonobo']]]];

        $root = $tree->createNode( 'Hominoidea', 'Hominoidea' );
        $tree->setRootNode( $root );

        $this->addChildren( $root, $primates['Hominoidea'] );
    }

    private function addChildren( ezcTreeNode $node, array $children )
    {
        foreach( $children as $name => $child )
        {
            if ( is_array( $child ) )
            {
                $newNode = $node->tree->createNode( $name, $name );
                $node->addChild( $newNode );
                $this->addChildren( $newNode, $child );
            }
            else
            {
                $newNode = $node->tree->createNode( $child, $child );
                $node->addChild( $newNode );
            }
        }
    }

    public function testVisitor1()
    {
        $tree = ezcTreeMemory::create( new ezcTreeMemoryDataStore() );
        $this->addTestData( $tree );

        $visitor = new ezcTreeVisitorGraphViz;
        $tree->accept( $visitor );
        self::assertSame( 'c422c6271ff3c9a213156e660a1ba8b2', md5( (string) $visitor ) );
    }

    public function testVisitor2()
    {
        $tree = ezcTreeMemory::create( new ezcTreeMemoryDataStore() );
        $this->addTestData( $tree );

        $expected = file_get_contents( __DIR__ . '/files/compare/visitor-visitor2.txt' );

        $visitor = new ezcTreeVisitorPlainText;
        $tree->accept( $visitor );
        self::assertSame( $expected, (string) $visitor );

        $visitor = new ezcTreeVisitorPlainText( ezcTreeVisitorPlainText::SYMBOL_UTF8 );
        $tree->accept( $visitor );
        self::assertSame( $expected, (string) $visitor );
    }

    public function testVisitor3()
    {
        $tree = ezcTreeMemory::create( new ezcTreeMemoryDataStore() );
        $this->addTestData( $tree );

        $visitor = new ezcTreeVisitorPlainText( ezcTreeVisitorPlainText::SYMBOL_ASCII );
        $tree->accept( $visitor );
        $expected = file_get_contents( __DIR__ . '/files/compare/visitor-visitor3.txt' );
        self::assertSame( $expected, (string) $visitor );
    }

    public function testVisitor4()
    {
        $tree = ezcTreeMemory::create( new ezcTreeMemoryDataStore() );

        $visitor = new ezcTreeVisitorPlainText( ezcTreeVisitorPlainText::SYMBOL_ASCII );
        $tree->accept( $visitor );
        $expected = "\n";
        self::assertSame( $expected, (string) $visitor );
    }

    public static function suite()
    {
         return new PHPUnit_Framework_TestSuite( "ezcTreeVisitorTest" );
    }
}

?>
