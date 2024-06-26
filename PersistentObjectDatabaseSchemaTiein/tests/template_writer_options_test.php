<?php
/**
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @version 1.3
 * @filesource
 * @package PersistentObjectDatabaseSchemaTiein
 * @subpackage Tests
 */

/**
 * @package PersistentObjectDatabaseSchemaTiein
 * @subpackage Tests
 */
class ezcPersistentObjectTemplateSchemaWriterOptionsTest extends ezcTestCase
{
    public static function suite()
    {
        return new PHPUnit_Framework_TestSuite(self::class );
    }

    public function testDefaultCtor()
    {
        $opts = new ezcPersistentObjectTemplateSchemaWriterOptions();
        
        $this->assertEquals(
            realpath( __DIR__ . '/../src/template_writer/templates' ),
            realpath( $opts->templatePath )
        );

        $this->assertEquals(
            '.',
            $opts->templateCompilePath
        );

        $this->assertFalse(
            $opts->overwrite
        );

        $this->assertEquals(
            '',
            $opts->classPrefix
        );
    }

    public function testNonDefaultCtor()
    {
        $opts = new ezcPersistentObjectTemplateSchemaWriterOptions(
            ['templatePath'        => __DIR__, 'templateCompilePath' => __DIR__, 'overwrite'           => true, 'classPrefix'         => 'foo']
        );
        
        $this->assertEquals(
            __DIR__,
            $opts->templatePath
        );

        $this->assertEquals(
            __DIR__,
            $opts->templateCompilePath
        );

        $this->assertTrue(
            $opts->overwrite
        );

        $this->assertEquals(
            'foo',
            $opts->classPrefix
        );
    }

    public function testSetSuccess()
    {
        $opts = new ezcPersistentObjectTemplateSchemaWriterOptions();

        $this->assertSetProperty(
            $opts,
            'templatePath',
            [__DIR__, '.']
        );
        $this->assertSetProperty(
            $opts,
            'templateCompilePath',
            [__DIR__, '.']
        );
        $this->assertSetProperty(
            $opts,
            'overwrite',
            [true, false]
        );
        $this->assertSetProperty(
            $opts,
            'classPrefix',
            ['foo', '']
        );
    }

    public function testSetFailure()
    {
        $opts = new ezcPersistentObjectTemplateSchemaWriterOptions();

        $this->assertSetPropertyFails(
            $opts,
            'templatePath',
            ['/some/weird/path/that/does/not/exist', __FILE__, true, 23, 42.23, [], new stdClass()]
        );
        $this->assertSetPropertyFails(
            $opts,
            'templateCompilePath',
            ['/some/weird/path/that/does/not/exist', __FILE__, true, 23, 42.23, [], new stdClass()]
        );
        $this->assertSetPropertyFails(
            $opts,
            'overwrite',
            ['foo', 23, 42.23, [], new stdClass()]
        );
        $this->assertSetPropertyFails(
            $opts,
            'classPrefix',
            [true, false, 23, 42.23, [], new stdClass()]
        );

        try
        {
            $opts->fooBar = true;
            $this->fail( 'Exception not thrown on set access to non existent property.' );
        }
        catch ( ezcBasePropertyNotFoundException $e ) {}
    }

    public function testGetPropertyFails()
    {
        $opts = new ezcPersistentObjectTemplateSchemaWriterOptions();

        try
        {
            echo $opts->fooBar;
            $this->fail( 'Exception not thrown on get access to non existent property.' );
        }
        catch ( ezcBasePropertyNotFoundException $e ) {}
    }

    public function testIsset()
    {
        $opts = new ezcPersistentObjectTemplateSchemaWriterOptions();

        $this->assertTrue(
            isset( $opts->templatePath )
        );
        $this->assertTrue(
            isset( $opts->templateCompilePath )
        );
        $this->assertTrue(
            isset( $opts->overwrite )
        );
        $this->assertTrue(
            isset( $opts->classPrefix )
        );
        $this->assertFalse(
            isset( $opts->fooBar )
        );
    }
}
?>
