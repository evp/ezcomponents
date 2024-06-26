<?php
/**
 * File containing the ezcDocument test suite
 *
 * @package Document
 * @version 1.3.1
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * 
 * A base class for document type handlers.
 *
 */

/**
* Required test suites.
*/
require 'tokenizer_tests.php';
require 'parser_tests.php';
require 'docbook_visitor_tests.php';

class ezcDocumentBBCodeSuite extends PHPUnit_Framework_TestSuite
{
    public static function suite()
    {
        return new ezcDocumentBBCodeSuite( self::class );
    }

    public function __construct()
    {
        parent::__construct();
        $this->setName( "Document BBCode tests" );

        $this->addTest( ezcDocumentBBCodeTokenizerTests::suite() );
        $this->addTest( ezcDocumentBBCodeParserTests::suite() );
        $this->addTest( ezcDocumentBBCodeDocbookVisitorTests::suite() );
    }
}

?>
