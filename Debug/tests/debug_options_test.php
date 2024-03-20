<?php
/**
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @version 1.2.1
 * @filesource
 * @package Debug
 * @subpackage Tests
 */

/**
 * Test suite for the ezcDebugOptions class.
 *
 * @package Debug
 * @subpackage Tests
 */
class ezcDebugOptionsTest extends ezcTestCase
{
    public static function suite()
    {
        return new PHPUnit_Framework_TestSuite( self::class );
    }

    public function testCtor()
    {
        $opts = new ezcDebugOptions();

        $this->assertSame(
            false,
            $opts->stackTrace,
            'Default value for option $stackTrace incorrect.'
        );
        $this->assertSame(
            5,
            $opts->stackTraceDepth,
            'Default value for option $stackTraceDepth incorrect.'
        );
    }

    public function testSetSuccess()
    {
        $opts = new ezcDebugOptions();

        $opts->stackTrace      = true;
        $opts->stackTraceDepth = 100;

        $this->assertSetProperty(
            $opts,
            'stackTrace',
            [true, false]
        );
        $this->assertSetProperty(
            $opts,
            'stackTraceDepth',
            [0, 1, 23]
        );
        $this->assertSetProperty(
            $opts,
            'stackTraceMaxData',
            [0, 1, 23, false]
        );
        $this->assertSetProperty(
            $opts,
            'stackTraceMaxChildren',
            [0, 1, 23, false]
        );
        $this->assertSetProperty(
            $opts,
            'stackTraceMaxDepth',
            [0, 1, 23, false]
        );
    }

    public function testSetFailure()
    {
        $opts = new ezcDebugOptions();

        $this->assertSetPropertyFails(
            $opts,
            'stackTrace',
            [null, 23, 'foobar', [], new stdClass()]
        );
        $this->assertSetPropertyFails(
            $opts,
            'stackTraceDepth',
            [null, true, -23, 'foobar', [], new stdClass()]
        );
        $this->assertSetPropertyFails(
            $opts,
            'stackTraceMaxData',
            [null, true, -23, 'foobar', [], new stdClass()]
        );
        $this->assertSetPropertyFails(
            $opts,
            'stackTraceMaxChildren',
            [null, true, -23, 'foobar', [], new stdClass()]
        );
        $this->assertSetPropertyFails(
            $opts,
            'stackTraceMaxDepth',
            [null, true, -23, 'foobar', [], new stdClass()]
        );
    }
}
?>
