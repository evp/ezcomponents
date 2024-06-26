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
 * Test case base for handler tests.
 *
 * @package Database
 * @subpackage Tests
 */
class ezcDatabaseHandlerBaseTest extends ezcTestCase
{
    protected $handlerClass;

    protected $db;

    protected function setUp()
    {
        try
        {
            $this->db = ezcDbInstance::get();
        }
        catch( Exception $e )
        {
            $this->markTestSkipped( 'Needs working DB connection to run this tests.' );
        }
        
        if ( $this->db === null )
        {
            $this->markTestSkipped( 'Cannot run bare handler base test.' );
        }

        if ( !( $this->db instanceof $this->handlerClass ) )
        {
            $this->markTestSkipped( "Test requires a database handler instance of class {$this->handlerClass}." );
        }

        $this->setUpTables();
        $this->setUpData();
    }

    protected function tearDown()
    {
        if ( $this->db === null ) return;

        $this->tearDownTables();
    }

    protected function setUpTables()
    {
        $schema = ezcDbSchema::createFromFile(
            'xml',
            __DIR__ . '/data/schema.xml'
        );
        $schema->writeToDb( $this->db );
    }

    protected function setUpData()
    {
        $id = $title = $description = null;

        $insert = $this->db->createInsertQuery();
        $insert->insertInto( $this->db->quoteIdentifier( 'books') )
            ->set( $this->db->quoteIdentifier( 'id' ), $insert->bindParam( $id ) )
            ->set( $this->db->quoteIdentifier( 'title' ), $insert->bindParam( $title ) )
            ->set( $this->db->quoteIdentifier( 'description' ), $insert->bindParam( $description ) );
        $stmt = $insert->prepare();

        $values = [[1, 'Harry Potter and the Deathly Hallows', 'Harry Potter episode 7, the final chapter.'], [2, 'Harry Potter and the Order of the Phoenix', 'Harry Potter episode 5.'], [3, 'Object-Oriented Metrics in Practice', 'Using Software Metrics to Characterize, Evaluate, and Improve the Design of Object-Oriented Systems.'], [4, 'Modern Information Retrieval', 'The classical source about information retrieval, second revision']];

        foreach ( $values as $row )
        {
            $id = $row[0]; $title = $row[1]; $description = $row[2];
            $stmt->execute();
        }

        $id = $firstname = $lastname = null;
        
        $insert = $this->db->createInsertQuery();
        $insert->insertInto( $this->db->quoteIdentifier( 'authors') )
               ->set( $this->db->quoteIdentifier( 'id' ), $insert->bindParam( $id ) )
               ->set( $this->db->quoteIdentifier( 'firstname' ), $insert->bindParam( $firstname ) )
               ->set( $this->db->quoteIdentifier( 'lastname' ), $insert->bindParam( $lastname ) );
        $stmt = $insert->prepare();

        $values = [[1, 'Berthier', 'Ribeiro-Neto'], [2, 'Ricardo', 'Baeza-Yates'], [3, 'J. K.', 'Rowling'], [4, 'Michele', 'Lanza'], [5, 'Radu', 'Marinescu']];

        foreach ( $values as $row )
        {
            $id = $row[0]; $firstname = $row[1]; $lastname = $row[2];
            $stmt->execute();
        }
       
        $bookId = $authorId = null;
        
        $insert = $this->db->createInsertQuery();
        $insert->insertInto( $this->db->quoteIdentifier( 'books_authors') )
               ->set( $this->db->quoteIdentifier( 'book_id' ), $insert->bindParam( $bookId ) )
               ->set( $this->db->quoteIdentifier( 'author_id' ), $insert->bindParam( $authorId ) );
        $stmt = $insert->prepare();

        $values = [[1, 3], [2, 3], [3, 4], [3, 5], [4, 1], [4, 2]];

        foreach ( $values as $row )
        {
            $bookId = $row[0]; $authorId = $row[1];
            $stmt->execute();
        }
        
        $bookId = $critique = null;
        
        $insert = $this->db->createInsertQuery();
        $insert->insertInto( $this->db->quoteIdentifier( 'ownership') )
               ->set( $this->db->quoteIdentifier( 'book_id' ), $insert->bindParam( $bookId ) )
               ->set( $this->db->quoteIdentifier( 'critique' ), $insert->bindParam( $critique ) );
        $stmt = $insert->prepare();

        $values = [[1, 'One of the best Harry Potter books. I really enjoyed reading it. Sad that the whole story ended now.'], [2, 'Another nice Harry Potter book. It is not one of the best, still i enjoyed it quite much.']];

        foreach ( $values as $row )
        {
            $bookId = $row[0]; $critique = $row[1];
            $stmt->execute();
        }
    }

    protected function tearDownTables()
    {
        $stmt = $this->db->prepare( 'DROP TABLE books' );
        $stmt->execute();
        $stmt->closeCursor();
        $stmt = $this->db->prepare( 'DROP TABLE authors' );
        $stmt->execute();
        $stmt->closeCursor();
        $stmt = $this->db->prepare( 'DROP TABLE books_authors' );
        $stmt->execute();
        $stmt->closeCursor();
        $stmt = $this->db->prepare( 'DROP TABLE ownership' );
        $stmt->execute();
        $stmt->closeCursor();
    }

    public function testSimpleSelect()
    {
        $query = $this->db->createSelectQuery();
        $query->select( '*' )->from( $this->db->quoteIdentifier( 'books' ) );

        $stmt = $query->prepare();
        $stmt->execute();

        $results = $stmt->fetchAll();
        $stmt->closeCursor();
        
        $expectedResults = [0 => 
        ['description' => 'Harry Potter episode 7, the final chapter.', 0 => 'Harry Potter episode 7, the final chapter.', 'id' => '1', 1 => '1', 'title' => 'Harry Potter and the Deathly Hallows', 2 => 'Harry Potter and the Deathly Hallows'], 1 => 
        ['description' => 'Harry Potter episode 5.', 0 => 'Harry Potter episode 5.', 'id' => '2', 1 => '2', 'title' => 'Harry Potter and the Order of the Phoenix', 2 => 'Harry Potter and the Order of the Phoenix'], 2 => 
        ['description' => 'Using Software Metrics to Characterize, Evaluate, and Improve the Design of Object-Oriented Systems.', 0 => 'Using Software Metrics to Characterize, Evaluate, and Improve the Design of Object-Oriented Systems.', 'id' => '3', 1 => '3', 'title' => 'Object-Oriented Metrics in Practice', 2 => 'Object-Oriented Metrics in Practice'], 3 => 
        ['description' => 'The classical source about information retrieval, second revision', 0 => 'The classical source about information retrieval, second revision', 'id' => '4', 1 => '4', 'title' => 'Modern Information Retrieval', 2 => 'Modern Information Retrieval']];

        $this->assertEquals(
            $expectedResults,
            $results,
            'Results not fetched correctly from DB.'
        );
    }

    public function testSelectWithSubselect()
    {
        $query = $this->db->createSelectQuery();

        $subQuery = $query->subSelect();
        $subQuery->select(
            $this->db->quoteIdentifier( 'ownership' ) . '.' . $this->db->quoteIdentifier( 'book_id' )
         )->from(
            $this->db->quoteIdentifier( 'ownership' )
         );

        $query->select( '*' )->from( $this->db->quoteIdentifier( 'books' ) )
              ->where(
                $query->expr->not(
                    $query->expr->in(
                        $this->db->quoteIdentifier( 'id' ), $subQuery
                    )
                )
        );

        $stmt = $query->prepare();
        $stmt->execute();

        $results = $stmt->fetchAll();

        $expectedResults = [0 => 
        ['description' => 'Using Software Metrics to Characterize, Evaluate, and Improve the Design of Object-Oriented Systems.', 0 => 'Using Software Metrics to Characterize, Evaluate, and Improve the Design of Object-Oriented Systems.', 'id' => '3', 1 => '3', 'title' => 'Object-Oriented Metrics in Practice', 2 => 'Object-Oriented Metrics in Practice'], 1 => 
        ['description' => 'The classical source about information retrieval, second revision', 0 => 'The classical source about information retrieval, second revision', 'id' => '4', 1 => '4', 'title' => 'Modern Information Retrieval', 2 => 'Modern Information Retrieval']];

        $this->assertEquals(
            $expectedResults,
            $results,
            'Results not fetched correctly from DB.'
        );
    }

}

?>
