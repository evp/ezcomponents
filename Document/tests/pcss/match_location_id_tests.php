<?php
/**
 * ezcDocumentPdfStyleInferenceTests
 * 
 * @package Document
 * @version 1.3.1
 * @subpackage Tests
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Test suite for class.
 * 
 * @package Document
 * @subpackage Tests
 */
class ezcDocumentPcssMatchLocationIdTests extends ezcTestCase
{
    protected $document;
    protected $xpath;

    public static function suite()
    {
        return new PHPUnit_Framework_TestSuite( self::class );
    }

    public function setUp()
    {
        $this->document = new DOMDocument();
        $this->document->registerNodeClass( 'DOMElement', 'ezcDocumentLocateableDomElement' );

        $this->document->load( __DIR__ . '/../files/docbook/pdf/location_ids.xml' );

        $this->xpath = new DOMXPath( $this->document );
        $this->xpath->registerNamespace( 'doc', 'http://docbook.org/ns/docbook' );
    }

    public function testMatchCommonRootNode()
    {
        $element = $this->xpath->query( '//doc:article' )->item( 0 );

        $directive = new ezcDocumentPcssLayoutDirective(
            ['article'],
            []
        );

        $this->assertEquals(
            true,
            (bool) preg_match( $regexp = $directive->getRegularExpression(), $id = $element->getLocationId() ),
            "Directive $regexp was expected to match elements location id: \"$id\"."
        );
    }

    public function testMatchExplicitRootNode()
    {
        $element = $this->xpath->query( '//doc:article' )->item( 0 );

        $directive = new ezcDocumentPcssLayoutDirective(
            ['> article'],
            []
        );

        $this->assertEquals(
            true,
            (bool) preg_match( $regexp = $directive->getRegularExpression(), $id = $element->getLocationId() ),
            "Directive $regexp was expected to match elements location id: \"$id\"."
        );
    }

    public function testNoMatchExplicitRootNode()
    {
        $element = $this->xpath->query( '//doc:section' )->item( 0 );

        $directive = new ezcDocumentPcssLayoutDirective(
            ['> section'],
            []
        );

        $this->assertEquals(
            false,
            (bool) preg_match( $regexp = $directive->getRegularExpression(), $id = $element->getLocationId() ),
            "Directive $regexp was expected to NOT match elements location id: \"$id\"."
        );
    }

    public function testNotMatchChildWithParentAssertion()
    {
        $element = $this->xpath->query( '//doc:section' )->item( 0 );

        $directive = new ezcDocumentPcssLayoutDirective(
            ['article'],
            []
        );

        $this->assertEquals(
            false,
            (bool) preg_match( $regexp = $directive->getRegularExpression(), $id = $element->getLocationId() ),
            "Directive $regexp was expected to NOT match elements location id: \"$id\"."
        );
    }

    public function testNoMatchRequiredId()
    {
        $element = $this->xpath->query( '//doc:article' )->item( 0 );

        $directive = new ezcDocumentPcssLayoutDirective(
            ['article', '#some_id'],
            []
        );

        $this->assertEquals(
            false,
            (bool) preg_match( $regexp = $directive->getRegularExpression(), $id = $element->getLocationId() ),
            "Directive $regexp was expected to NOT match elements location id: \"$id\"."
        );
    }

    public function testNoMatchRequiredClass()
    {
        $element = $this->xpath->query( '//doc:article' )->item( 0 );

        $directive = new ezcDocumentPcssLayoutDirective(
            ['article', '.class'],
            []
        );

        $this->assertEquals(
            false,
            (bool) preg_match( $regexp = $directive->getRegularExpression(), $id = $element->getLocationId() ),
            "Directive $regexp was expected to NOT match elements location id: \"$id\"."
        );
    }

    public function testNoMatchRequiredClassAndId()
    {
        $element = $this->xpath->query( '//doc:article' )->item( 0 );

        $directive = new ezcDocumentPcssLayoutDirective(
            ['article', '.class', '#some_id'],
            []
        );

        $this->assertEquals(
            false,
            (bool) preg_match( $regexp = $directive->getRegularExpression(), $id = $element->getLocationId() ),
            "Directive $regexp was expected to NOT match elements location id: \"$id\"."
        );
    }

    public function testMatchNodeWithId()
    {
        $element = $this->xpath->query( '//doc:section' )->item( 0 );

        $directive = new ezcDocumentPcssLayoutDirective(
            ['section', '#paragraph_with_inline_markup'],
            []
        );

        $this->assertEquals(
            true,
            (bool) preg_match( $regexp = $directive->getRegularExpression(), $id = $element->getLocationId() ),
            "Directive $regexp was expected to match elements location id: \"$id\"."
        );
    }

    public function testMatchAnyDescendant()
    {
        $element = $this->xpath->query( '//doc:section' )->item( 0 );

        $directive = new ezcDocumentPcssLayoutDirective(
            ['article', 'section'],
            []
        );

        $this->assertEquals(
            true,
            (bool) preg_match( $regexp = $directive->getRegularExpression(), $id = $element->getLocationId() ),
            "Directive $regexp was expected to match elements location id: \"$id\"."
        );
    }

    public function testMatchDirectDescendant()
    {
        $element = $this->xpath->query( '//doc:section' )->item( 0 );

        $directive = new ezcDocumentPcssLayoutDirective(
            ['article', 'section'],
            []
        );

        $this->assertEquals(
            true,
            (bool) preg_match( $regexp = $directive->getRegularExpression(), $id = $element->getLocationId() ),
            "Directive $regexp was expected to match elements location id: \"$id\"."
        );
    }

    public function testMatchAnyDescendentIgnoreId()
    {
        $element = $this->xpath->query( '//doc:sectioninfo' )->item( 0 );

        $directive = new ezcDocumentPcssLayoutDirective(
            ['article', 'sectioninfo'],
            []
        );

        $this->assertEquals(
            true,
            (bool) preg_match( $regexp = $directive->getRegularExpression(), $id = $element->getLocationId() ),
            "Directive $regexp was expected to match elements location id: \"$id\"."
        );
    }

    public function testNotMatchDirectDescendent()
    {
        $element = $this->xpath->query( '//doc:sectioninfo' )->item( 0 );

        $directive = new ezcDocumentPcssLayoutDirective(
            ['article', '> sectioninfo'],
            []
        );

        $this->assertEquals(
            false,
            (bool) preg_match( $regexp = $directive->getRegularExpression(), $id = $element->getLocationId() ),
            "Directive $regexp was expected to NOT match elements location id: \"$id\"."
        );
    }

    public function testNotMatchPartialId()
    {
        $element = $this->xpath->query( '//doc:sectioninfo' )->item( 0 );

        $directive = new ezcDocumentPcssLayoutDirective(
            ['section', '#paragraph'],
            []
        );

        $this->assertEquals(
            false,
            (bool) preg_match( $regexp = $directive->getRegularExpression(), $id = $element->getLocationId() ),
            "Directive $regexp was expected to NOT match elements location id: \"$id\"."
        );
    }

    public function testMatchByClassName()
    {
        $element = $this->xpath->query( '//doc:para' )->item( 1 );

        $directive = new ezcDocumentPcssLayoutDirective(
            ['para', '.note_warning'],
            []
        );

        $this->assertEquals(
            true,
            (bool) preg_match( $regexp = $directive->getRegularExpression(), $id = $element->getLocationId() ),
            "Directive $regexp was expected to match elements location id: \"$id\"."
        );
    }

    public function testMatchByPartialClassName()
    {
        $element = $this->xpath->query( '//doc:para' )->item( 1 );

        $directive = new ezcDocumentPcssLayoutDirective(
            ['para', '.note'],
            []
        );

        $this->assertEquals(
            true,
            (bool) preg_match( $regexp = $directive->getRegularExpression(), $id = $element->getLocationId() ),
            "Directive $regexp was expected to match elements location id: \"$id\"."
        );
    }

    public function testMatchByPartialClassName2()
    {
        $element = $this->xpath->query( '//doc:para' )->item( 1 );

        $directive = new ezcDocumentPcssLayoutDirective(
            ['para', '.warning'],
            []
        );

        $this->assertEquals(
            true,
            (bool) preg_match( $regexp = $directive->getRegularExpression(), $id = $element->getLocationId() ),
            "Directive $regexp was expected to match elements location id: \"$id\"."
        );
    }

    public function testNotMatchByPartialClassName()
    {
        $element = $this->xpath->query( '//doc:para' )->item( 1 );

        $directive = new ezcDocumentPcssLayoutDirective(
            ['para', '.not'],
            []
        );

        $this->assertEquals(
            false,
            (bool) preg_match( $regexp = $directive->getRegularExpression(), $id = $element->getLocationId() ),
            "Directive $regexp was expected to NOT match elements location id: \"$id\"."
        );
    }

    public function testMatchOnlyByClassName()
    {
        $element = $this->xpath->query( '//doc:para' )->item( 1 );

        $directive = new ezcDocumentPcssLayoutDirective(
            ['.note'],
            []
        );

        $this->assertEquals(
            true,
            (bool) preg_match( $regexp = $directive->getRegularExpression(), $id = $element->getLocationId() ),
            "Directive $regexp was expected to match elements location id: \"$id\"."
        );
    }

    public function testNotMatchOnlyByClassName()
    {
        $element = $this->xpath->query( '//doc:para' )->item( 0 );

        $directive = new ezcDocumentPcssLayoutDirective(
            ['.note'],
            []
        );

        $this->assertEquals(
            false,
            (bool) preg_match( $regexp = $directive->getRegularExpression(), $id = $element->getLocationId() ),
            "Directive $regexp was expected to NOT match elements location id: \"$id\"."
        );
    }

    public function testMatchOnlyById()
    {
        $element = $this->xpath->query( '//doc:section' )->item( 0 );

        $directive = new ezcDocumentPcssLayoutDirective(
            ['#paragraph_with_inline_markup'],
            []
        );

        $this->assertEquals(
            true,
            (bool) preg_match( $regexp = $directive->getRegularExpression(), $id = $element->getLocationId() ),
            "Directive $regexp was expected to match elements location id: \"$id\"."
        );
    }

    public function testNotMatchOnlyById()
    {
        $element = $this->xpath->query( '//doc:article' )->item( 0 );

        $directive = new ezcDocumentPcssLayoutDirective(
            ['#paragraph_with_inline_markup'],
            []
        );

        $this->assertEquals(
            false,
            (bool) preg_match( $regexp = $directive->getRegularExpression(), $id = $element->getLocationId() ),
            "Directive $regexp was expected to NOT match elements location id: \"$id\"."
        );
    }

    public function testNotMatchChildOnlyById()
    {
        $element = $this->xpath->query( '//doc:para' )->item( 0 );

        $directive = new ezcDocumentPcssLayoutDirective(
            ['#paragraph_with_inline_markup'],
            []
        );

        $this->assertEquals(
            false,
            (bool) preg_match( $regexp = $directive->getRegularExpression(), $id = $element->getLocationId() ),
            "Directive $regexp was expected to NOT match elements location id: \"$id\"."
        );
    }
}

?>
