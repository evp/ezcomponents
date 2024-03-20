<?php
/**
 * @copyright Copyright (C) 2005-2008 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @version 1.1.1
 * @filesource
 * @package SignalSlot
 * @subpackage Tests
 */

require_once( "test_classes.php" );

/**
 * @package SignalSlot
 * @subpackage Tests
 * @TODO: test slots with params by reference
 * @TODO: test with invalid priority input
 */
class ezcSignalCollectionTest extends ezcTestCase
{
    private $giver;
    private $receiver;

    protected function setUp()
    {
        $this->giver = new TheGiver();
        $this->receiver = new TheReceiver();
        TheReceiver::$globalFunctionRun = false;
        TheReceiver::$staticFunctionRun = false;
        ezcSignalStaticConnections::getInstance()->connections = [];
        ezcSignalCollection::setStaticConnectionsHolder( new EmptyStaticConnections() );
    }

    public function testSignalsBlocked()
    {
        $this->giver->signals->connect( "signal", [$this->receiver, "slotNoParams1"] );
        $this->giver->signals->signalsBlocked = true;
        $this->giver->signals->emit( "signal" );
        $this->assertEquals( [], $this->receiver->stack );
    }

    public function testSingleSignalNoParamsNoPri()
    {
        $this->giver->signals->connect( "signal", [$this->receiver, "slotNoParams1"] );
        $this->giver->signals->emit( "signal" );
        $this->assertEquals( ["slotNoParams1"], $this->receiver->stack );
    }

    public function testSingleSignalOneParamNoPri()
    {
        $this->giver->signals->connect( "signal", [$this->receiver, "slotSingleParam"] );
        $this->giver->signals->emit( "signal", "on" );
        $this->assertEquals( ["on"], $this->receiver->stack );
    }

    public function testSingleSignalTwoParamsNoPri()
    {
        $this->giver->signals->connect( "signal", [$this->receiver, "slotDoubleParams"] );
        $this->giver->signals->emit( "signal", "the", "turning" );
        $this->assertEquals( ["theturning"], $this->receiver->stack );
    }

    public function testSingleSignalsThreeParamsNoPri()
    {
        $this->giver->signals->connect( "signal", [$this->receiver, "slotTrippleParams"] );
        $this->giver->signals->emit( "signal", "away", "sorrow", "money" );
        $this->assertEquals( ["awaysorrowmoney"], $this->receiver->stack );
    }

    public function testSingleSignalsZeroOrMoreParamsNoPri()
    {
        $this->giver->signals->connect( "signal", [$this->receiver, "slotZeroOrMoreParams"] );
        $this->giver->signals->emit( "signal", "A", "great", "day", "comrades,", "we", "sail", "into", "history!" );
        $this->assertEquals( ["A great day comrades, we sail into history!"], $this->receiver->stack );
    }

    public function testSingleSignalsOneOrMoreParamsNoPri()
    {
        $this->giver->signals->connect( "signal", [$this->receiver, "slotOneOrMoreParams"] );
        $this->giver->signals->emit( "signal", "Understanding", "is", "a", "three-edged", "sword." );
        $this->assertEquals( ["Understanding is a three-edged sword."], $this->receiver->stack );
    }

    public function testThreeSignalsNoParamNoPri()
    {
        $this->giver->signals->connect( "signal1", [$this->receiver, "slotNoParams1"] );
        $this->giver->signals->connect( "signal2", [$this->receiver, "slotNoParams2"] );
        $this->giver->signals->connect( "signal3", [$this->receiver, "slotNoParams3"] );
        $this->giver->signals->emit( "signal2" );
        $this->giver->signals->emit( "signal1" );
        $this->giver->signals->emit( "signal3" );
        $this->assertEquals( ["slotNoParams2", "slotNoParams1", "slotNoParams3"], $this->receiver->stack );
    }

    public function testThreeSlotsNoParamNoPri()
    {
        $this->giver->signals->connect( "signal", [$this->receiver, "slotNoParams2"] );
        $this->giver->signals->connect( "signal", [$this->receiver, "slotNoParams3"] );
        $this->giver->signals->connect( "signal", [$this->receiver, "slotNoParams1"] );
        $this->giver->signals->emit( "signal" );
        $this->assertEquals( ["slotNoParams2", "slotNoParams3", "slotNoParams1"], $this->receiver->stack );
    }

    public function testPriorityFiveSlotsSingleSignal()
    {
        $this->giver->signals->connect( "signal", [$this->receiver, "slotNoParams2"], 1001 );
        $this->giver->signals->connect( "signal", [$this->receiver, "slotNoParams5"], 9999 );
        $this->giver->signals->connect( "signal", [$this->receiver, "slotNoParams3"] );
        $this->giver->signals->connect( "signal", [$this->receiver, "slotNoParams1"], 1 );
        $this->giver->signals->connect( "signal", [$this->receiver, "slotNoParams4"], 999 );
        $this->giver->signals->emit( "signal" );
        $this->assertEquals( ["slotNoParams1", "slotNoParams4", "slotNoParams3", "slotNoParams2", "slotNoParams5"],
                             $this->receiver->stack );
    }

    public function testPriorityFiveSlotsMultiSignal()
    {
        $this->giver->signals->connect( "signal", [$this->receiver, "slotNoParams2"], 1001 );
        $this->giver->signals->connect( "signal", [$this->receiver, "slotNoParams5"], 9999 );
        $this->giver->signals->connect( "signal", [$this->receiver, "slotNoParams3"] );
        $this->giver->signals->connect( "signal", [$this->receiver, "slotNoParams1"], 1 );
        $this->giver->signals->connect( "signal", [$this->receiver, "slotNoParams4"], 999 );

        $this->giver->signals->connect( "signal2", [$this->receiver, "slotNoParams1"], 1001 );
        $this->giver->signals->connect( "signal2", [$this->receiver, "slotNoParams2"], 9999 );
        $this->giver->signals->connect( "signal2", [$this->receiver, "slotNoParams4"] );
        $this->giver->signals->connect( "signal2", [$this->receiver, "slotNoParams3"], 1 );
        $this->giver->signals->connect( "signal2", [$this->receiver, "slotNoParams5"], 999 );

        $this->giver->signals->emit( "signal" );
        $this->giver->signals->emit( "signal2" );
        $this->assertEquals( ["slotNoParams1", "slotNoParams4", "slotNoParams3", "slotNoParams2", "slotNoParams5", "slotNoParams3", "slotNoParams5", "slotNoParams4", "slotNoParams1", "slotNoParams2"],
                             $this->receiver->stack );
    }

    public function testGlobalSlot()
    {
        $this->giver->signals->connect( "signal", "slotFunction" );
        $this->giver->signals->emit( "signal" );
        $this->assertEquals( "brain damage", TheReceiver::$globalFunctionRun );
    }

    public function testStaticSlot()
    {
        $this->giver->signals->connect( "signal", ["TheReceiver", "slotStatic"]  );
        $this->giver->signals->emit( "signal" );
        $this->assertEquals( "have a cigar", TheReceiver::$staticFunctionRun );
    }

    public function testDisconnect()
    {
        $this->giver->signals->connect( "signal", [$this->receiver, "slotNoParams2"] );
        $this->giver->signals->connect( "signal", [$this->receiver, "slotNoParams3"] );
        $this->giver->signals->connect( "signal", [$this->receiver, "slotNoParams1"] );

        $this->giver->signals->disconnect( "signal", [$this->receiver, "slotNoParams3"] );
        $this->giver->signals->emit( "signal" );
        $this->assertEquals( ["slotNoParams2", "slotNoParams1"], $this->receiver->stack );
    }

    public function testAdvancedDisconnectNoPriority()
    {
        $this->giver->signals->connect( "signal", [$this->receiver, "slotNoParams2"], 5000 );
        $this->giver->signals->connect( "signal", [$this->receiver, "slotNoParams3"] );
        $this->giver->signals->connect( "signal", [$this->receiver, "slotNoParams1"], 10 );

        $this->giver->signals->disconnect( "signal", [$this->receiver, "slotNoParams3"] );
        $this->giver->signals->emit( "signal" );
        $this->assertEquals( ["slotNoParams1", "slotNoParams2"], $this->receiver->stack );
    }

    public function testAdvancedDisconnectNoPrioritySeveralConnections()
    {
        $this->giver->signals->connect( "signal", [$this->receiver, "slotNoParams2"], 5000 );
        $this->giver->signals->connect( "signal", [$this->receiver, "slotNoParams3"] );
        $this->giver->signals->connect( "signal", [$this->receiver, "slotNoParams3"], 1 );
        $this->giver->signals->connect( "signal", [$this->receiver, "slotNoParams1"], 10 );

        $this->giver->signals->disconnect( "signal", [$this->receiver, "slotNoParams3"] );
        $this->giver->signals->emit( "signal" );
        $this->assertEquals( ["slotNoParams3", "slotNoParams1", "slotNoParams2"], $this->receiver->stack );
    }

    public function testAdvancedDisconnectNoPrioritySeveralConnections2()
    {
        $this->giver->signals->connect( "signal", [$this->receiver, "slotNoParams2"], 5000 );
        $this->giver->signals->connect( "signal", [$this->receiver, "slotNoParams3"] );
        $this->giver->signals->connect( "signal", [$this->receiver, "slotNoParams3"], 5001 );
        $this->giver->signals->connect( "signal", [$this->receiver, "slotNoParams1"], 10 );

        $this->giver->signals->disconnect( "signal", [$this->receiver, "slotNoParams3"] );
        $this->giver->signals->emit( "signal" );
        $this->assertEquals( ["slotNoParams1", "slotNoParams3", "slotNoParams2"], $this->receiver->stack );
    }


    public function testAdvancedDisconnectPriority()
    {
        $this->giver->signals->connect( "signal", [$this->receiver, "slotNoParams2"], 5000 );
        $this->giver->signals->connect( "signal", [$this->receiver, "slotNoParams3"] );
        $this->giver->signals->connect( "signal", [$this->receiver, "slotNoParams1"], 10 );

        $this->giver->signals->disconnect( "signal", [$this->receiver, "slotNoParams3"], 1000 );
        $this->giver->signals->emit( "signal" );
        $this->assertEquals( ["slotNoParams1", "slotNoParams2"], $this->receiver->stack );
    }

    public function testAdvancedDisconnectPrioritySeveralConnections()
    {
        $this->giver->signals->connect( "signal", [$this->receiver, "slotNoParams2"], 5000 );
        $this->giver->signals->connect( "signal", [$this->receiver, "slotNoParams3"] );
        $this->giver->signals->connect( "signal", [$this->receiver, "slotNoParams3"], 1 );
        $this->giver->signals->connect( "signal", [$this->receiver, "slotNoParams1"], 10 );

        $this->giver->signals->disconnect( "signal", [$this->receiver, "slotNoParams3"], 1 );
        $this->giver->signals->emit( "signal" );
        $this->assertEquals( ["slotNoParams1", "slotNoParams3", "slotNoParams2"], $this->receiver->stack );
    }

    public function testIsConnectedNoConnections()
    {
        $this->assertEquals( false, $this->giver->signals->isConnected( 'signal' ) );
    }

    public function testIsConnectedNormalConnection()
    {
        $this->giver->signals->connect( "signal", [$this->receiver, "slotNoParams3"] );
        $this->assertEquals( true, $this->giver->signals->isConnected( 'signal' ) );
    }

    public function testIsConnectedNormalConnectionDisconnected()
    {
        $this->giver->signals->connect( "signal", [$this->receiver, "slotNoParams3"] );
        $this->giver->signals->disconnect( "signal", [$this->receiver, "slotNoParams3"] );
        $this->assertEquals( false, $this->giver->signals->isConnected( 'signal' ) );
    }

    public function testIsConnectedPriorityConnected()
    {
        $this->giver->signals->connect( "signal", [$this->receiver, "slotNoParams3"], 1 );
        $this->assertEquals( true, $this->giver->signals->isConnected( 'signal' ) );
    }

    public function testIsConnectedPriorityDisconnected()
    {
        $this->giver->signals->connect( "signal", [$this->receiver, "slotNoParams3"], 1 );
        $this->giver->signals->disconnect( "signal", [$this->receiver, "slotNoParams3"], 1 );
        $this->assertEquals( false, $this->giver->signals->isConnected( 'signal' ) );
    }

    public function testIsConnectedStaticConnection()
    {
        ezcSignalStaticConnections::getInstance()->connect( 'TheGiver', 'signal', 'slotFunction' );
        $this->assertEquals( true, $this->giver->signals->isConnected( 'signal' ) );
    }

    public function testIsConnectedStaticConnectionDisconnected()
    {
        ezcSignalStaticConnections::getInstance()->connect( 'TheGiver', 'signal', 'slotFunction' );
        ezcSignalStaticConnections::getInstance()->disconnect( 'TheGiver', 'signal', 'slotFunction' );
        $this->assertEquals( false, $this->giver->signals->isConnected( 'signal' ) );
    }

    public static function suite()
    {
         return new PHPUnit_Framework_TestSuite( "ezcSignalCollectionTest" );
    }
}
?>
