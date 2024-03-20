<?php
/**
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @version 1.4
 * @filesource
 * @package EventLog
 * @subpackage Tests
 */

/**
 * @package EventLog
 * @subpackage Tests
 */
class ezcLogContextTest extends ezcTestCase
{
    protected $context;

    protected function setUp()
    {
        $this->context = new ezcLogContext();
    }
    
    public function testEventTypeGetSet()
    {
        $this->context->setSeverityContext( 2 | 4 | 16, ["Username" => "Bart Simpson", "Radio station" => "SlayRadio"] );

        $this->assertEquals(["Username" => "Bart Simpson", "Radio station" => "SlayRadio"], $this->context->getContext( 4, "anything") );

        $this->context->setSeverityContext( 1 | 32, ["Car" => "Red Ferrari"] );
        $this->assertEquals(["Car" => "Red Ferrari"], $this->context->getContext( 1, "anything") );
    }

    public function testEventTypeAddMultiple()
    {
        $this->context->setSeverityContext( 2 | 4 | 16, ["A" => "a"] );
        $this->context->setSeverityContext( 4 | 8 | 16, ["B" => "b"] );
        $this->context->setSeverityContext( 8 | 16| 32, ["C" => "c", "D" => "d"] );

        $this->assertEquals(["A" => "a"] , $this->context->getContext( 2, "anything" ) );
        $this->assertEquals(["A" => "a", "B" => "b"] , $this->context->getContext( 4, "anything" ) );
        $this->assertEquals(["B" => "b", "C" => "c", "D" => "d"] , $this->context->getContext( 8, "anything" ) );
        $this->assertEquals(["A" => "a", "B" => "b", "C" => "c", "D" => "d"] , $this->context->getContext( 16, "anything" ) );
        $this->assertEquals(["C" => "c", "D" => "d"] , $this->context->getContext( 32, "anything" ) );
    }

    public function testEventTypeNotExist()
    {
        $this->assertEquals([] , $this->context->getContext( 2, "anything" ) );

        $this->context->setSeverityContext( 2 | 4 | 16, ["A" => "a"] );
        $this->assertEquals( ["A" => "a"], $this->context->getContext( 16, "anything" ) );

        $this->assertEquals( [], $this->context->getContext( 8, "anything" ) );
    }
    
    public function testEventTypeOverride()
    {
        $this->context->setSeverityContext( 2 | 4 | 16, ["A" => "a"] );
        $this->context->setSeverityContext( 2 | 16, ["A" => "b"] );
        $this->assertEquals( ["A" => "b"], $this->context->getContext( 16, "anything" ) );
        $this->assertEquals( ["A" => "b"], $this->context->getContext( 2, "anything" ) );
        $this->assertEquals( ["A" => "a"], $this->context->getContext( 4, "anything" ) );
    }

    public function testEventTypeDelete()
    {
        $this->context->setSeverityContext( 2 | 4 | 16, ["A" => "a"] );
        $this->context->unsetSeverityContext( 4 | 16 | 32 );
        $this->assertEquals( ["A" => "a"], $this->context->getContext( 2, "anything" ) );
        $this->assertEquals( [], $this->context->getContext( 4, "anything" ) );
        $this->assertEquals( [], $this->context->getContext( 16, "anything" ) );
        $this->assertEquals( [], $this->context->getContext( 32, "anything" ) );
    }
     
   // //////////////// Same tests but now for the event source // /////////////////////// 

    public function testEventSourceGetSet()
    {
        $this->context->setSourceContext( ["s2", "s4", "s16"], ["Username" => "Bart Simpson", "Radio station" => "SlayRadio"] );

        $this->assertEquals(["Username" => "Bart Simpson", "Radio station" => "SlayRadio"], $this->context->getContext( 1, "s4") );

        $this->context->setSourceContext( ["s1", "s32"], ["Car" => "Red Ferrari"] );
        $this->assertEquals(["Car" => "Red Ferrari"], $this->context->getContext( 1, "s1") );
    }

    public function testEventSourceAddMultiple()
    {
        $this->context->setSourceContext( ["s2", "s4", "s16"], ["A" => "a"] );
        $this->context->setSourceContext( ["s4", "s8", "s16"], ["B" => "b"] );
        $this->context->setSourceContext( ["s8", "s16", "s32"], ["C" => "c", "D" => "d"] );

        $this->assertEquals(["A" => "a"] , $this->context->getContext( 2, "s2" ) );
        $this->assertEquals(["A" => "a", "B" => "b"] , $this->context->getContext( 4, "s4" ) );
        $this->assertEquals(["B" => "b", "C" => "c", "D" => "d"] , $this->context->getContext( 8, "s8" ) );
        $this->assertEquals(["A" => "a", "B" => "b", "C" => "c", "D" => "d"] , $this->context->getContext( 16, "s16" ) );
        $this->assertEquals(["C" => "c", "D" => "d"] , $this->context->getContext( 32, "s32" ) );
    }

    public function testEventSourceNotExist()
    {
        $this->assertEquals([] , $this->context->getContext( 2, "s2" ) );

        $this->context->setSourceContext( ["s2", "s4", "s16"], ["A" => "a"] );
        $this->assertEquals( ["A" => "a"], $this->context->getContext( 16, "s16" ) );

        $this->assertEquals( [], $this->context->getContext( 8, "s8" ) );
    }
    
    public function testEventSourceOverride()
    {
        $this->context->setSourceContext( ["s2", "s4", "s16"], ["A" => "a"] );
        $this->context->setSourceContext( ["s2", "s16"], ["A" => "b"] );
        $this->assertEquals( ["A" => "b"], $this->context->getContext( 16, "s16" ) );
        $this->assertEquals( ["A" => "b"], $this->context->getContext( 2, "s2" ) );
        $this->assertEquals( ["A" => "a"], $this->context->getContext( 4, "s4" ) );
    }

    public function testEventSourceDelete()
    {
        $this->context->setSourceContext( ["s2", "s4", "s16"], ["A" => "a"] );
        $this->context->unsetSourceContext( ["s4", "s16", "s32"] );
        $this->assertEquals( ["A" => "a"], $this->context->getContext( 2, "s2" ) );
        $this->assertEquals( [], $this->context->getContext( 4, "s4" ) );
        $this->assertEquals( [], $this->context->getContext( 16, "s16" ) );
        $this->assertEquals( [], $this->context->getContext( 32, "s32" ) );
    }
 


    public function testEventSourceStrangeNames()
    {
        $this->context->setSourceContext( [2, "s2", "A name", "A pretty long name for a source", "using `quotes` and 'stuff' like \"that\""], ["A" => "a"] );

        $this->assertEquals( ["A" => "a"], $this->context->getContext( 2, "s2" ) );
        $this->assertEquals( ["A" => "a"], $this->context->getContext( 2, 2 ) );
        $this->assertEquals( ["A" => "a"], $this->context->getContext( 2, "A name" ) );
        $this->assertEquals( [], $this->context->getContext( 2, "Doesn't exist" ) );
        $this->assertEquals( ["A" => "a"], $this->context->getContext( 2, "A pretty long name for a source" ) );
        $this->assertEquals( ["A" => "a"], $this->context->getContext( 2, "using `quotes` and 'stuff' like \"that\"" ) );
    }
 
   // //////////////// Testing both  // ////////////////////// 

   public function testCombination()
   {
        $this->context->setSourceContext( ["s2", "s4", "s16"], ["A" => "a"] );
        $this->context->setSeverityContext( 1 | 2 | 8, ["B" => "b"] );

        $this->assertEquals( ["B" => "b", "A" => "a"], $this->context->getContext( 2, "s2" ) );
        $this->assertEquals( ["B" => "b", "A" => "a"], $this->context->getContext( 1, "s4" ) );
        $this->assertEquals( ["B" => "b"], $this->context->getContext( 1, "SlayRadio" ) );
        $this->assertEquals( ["A" => "a"], $this->context->getContext( 42, "s16" ) );
        $this->assertEquals( [], $this->context->getContext( 32, "s32" ) );
   }

   public function testReset()
   {
        $this->context->setSourceContext( ["s2", "s4", "s16"], ["A" => "a"] );
        $this->context->setSeverityContext( 1 | 2 | 8, ["B" => "b"] );

        $this->assertEquals( ["B" => "b", "A" => "a"], $this->context->getContext( 2, "s2" ) );

        $this->context->reset();
        $this->assertEquals( [], $this->context->getContext( 2, "s2" ) );

        $this->context->setSourceContext( ["s2", "s4", "s16"], ["A" => "a"] );
        $this->context->setSeverityContext( 1 | 2 | 8, ["C" => "c"] );
        $this->assertEquals( ["C" => "c", "A" => "a"], $this->context->getContext( 2, "s2" ) );
   }
   

	public static function suite()
	{
		return new PHPUnit_Framework_TestSuite("ezcLogContextTest");
	}
}

?>
