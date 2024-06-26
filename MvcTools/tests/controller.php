<?php
/**
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @version 1.1.3
 * @filesource
 * @package MvcTools
 * @subpackage Tests
 */
require_once 'MvcTools/tests/testfiles/controller.php';

/**
 * Test the handler classes.
 *
 * @package MvcTools
 * @subpackage Tests
 */
class ezcMvcToolsControllerTest extends ezcTestCase
{
    public function testEmptyAction()
    {
        try
        {
            $f = new testControllerController( null, new ezcMvcRequest() );
            self::fail( 'Expected exception not thrown.' );
        }
        catch ( ezcMvcControllerException $e )
        {
            self::assertEquals( "The 'testControllerController' controller requires an action.", $e->getMessage() );
        }
    }

    public function testSetAction()
    {
        $f = new testControllerController( 'testAction', new ezcMvcRequest() );
        self::assertEquals( "testAction", $this->readAttribute( $f, 'action' ) );
    }

    public function testGetNonExistingVariables()
    {
        $r = new ezcMvcRequest;
        $r->variables = ['var1' => 42, 'var42' => 'bansai!'];
        $f = new testControllerController( 'testAction', $r );

        try
        {
            $foo = $f->new;
            self::fail( 'Expected exception not thrown.' );
        }
        catch ( ezcBasePropertyNotFoundException $e )
        {
            self::assertEquals( "No such property name 'new'.", $e->getMessage() );
        }
    }

    public function testSetVariables()
    {
        $r = new ezcMvcRequest;
        $r->variables = ['var1' => 42, 'var42' => 'bansai!'];
        $f = new testControllerController( 'testAction', $r );

        self::assertEquals( 42, $f->var1 );
        self::assertEquals( 'bansai!', $f->var42 );
    }

    public function testSetProperties()
    {
        $r = new ezcMvcRequest;
        $r->variables = ['var1' => 42, 'var42' => 'bansai!'];
        $f = new testControllerController( 'testAction', $r );

        try
        {
            $f->new = 'fail!';
            self::fail( 'Expected exception not thrown.' );
        }
        catch ( ezcBasePropertyPermissionException $e )
        {
            self::assertEquals( "The property 'new' is read-only.", $e->getMessage() );
        }

        try
        {
            $f->var = 'modified';
            self::fail( 'Expected exception not thrown.' );
        }
        catch ( ezcBasePropertyPermissionException $e )
        {
            self::assertEquals( "The property 'var' is read-only.", $e->getMessage() );
        }
    }

    public function testIssetProperties()
    {
        $r = new ezcMvcRequest;
        $r->variables = ['var1' => 42, 'var42' => 'bansai!'];
        $f = new testControllerController( 'testAction', $r );

        self::assertEquals( false, isset( $f->notSet ) );
        self::assertEquals( true, isset( $f->var1 ) );
    }

    public function testRoutingInformation()
    {
        $r = new ezcMvcRequest;
        $r->variables = ['var1' => 42, 'var42' => 'bansai!'];
        $f = new testControllerController( 'testAction', $r );
        $f->setRouter( new testSimpleRouter( $r ) );

        self::assertEquals( new testSimpleRouter( $r ), $f->getRouter() );
    }

    public function testCreateActionMethod()
    {
        $f = new testControllerController( 'test', new ezcMvcRequest() );
        self::assertEquals( 'doTest', $f->testCreateActionMethod() );

        $f = new testControllerController( 'test_action', new ezcMvcRequest() );
        self::assertEquals( 'doTestAction', $f->testCreateActionMethod() );

        $f = new testControllerController( 'testAction', new ezcMvcRequest() );
        self::assertEquals( 'doTestAction', $f->testCreateActionMethod() );

        $f = new testControllerController( 'test_with_more_than_OneWord', new ezcMvcRequest() );
        self::assertEquals( 'doTestWithMoreThanOneWord', $f->testCreateActionMethod() );
    }

    public static function suite()
    {
         return new PHPUnit_Framework_TestSuite( "ezcMvcToolsControllerTest" );
    }
}
?>
