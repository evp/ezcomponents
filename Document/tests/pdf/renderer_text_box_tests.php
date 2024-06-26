<?php
/**
 * ezcDocumentPdfDriverTcpdfTests
 * 
 * @package Document
 * @version 1.3.1
 * @subpackage Tests
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

require_once 'renderer_text_box_base_tests.php';

/**
 * Test suite for class.
 * 
 * @package Document
 * @subpackage Tests
 */
class ezcDocumentPdfTextBoxRendererTests extends ezcDocumentPdfTextBoxRendererBaseTests
{
    /**
     * Renderer used for the tests
     * 
     * @var string
     */
    protected $renderer = 'ezcDocumentPdfTextBoxRenderer';

    public static function suite()
    {
        return new PHPUnit_Framework_TestSuite( self::class );
    }

}

?>
