<?php
/**
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @version 1.7.1
 * @filesource
 * @package PersistentObject
 * @subpackage Tests
 */

/**
 * Tests ezcPersistentObject implementation without a ctor.
 * 
 * @package PersistentObject
 * @subpackage Tests
 */
class ezcPersistentTestObjectNoCtor implements ezcPersistentObject
{
    public function getState()
    {
        // Dummy
    }

    public function setState( array $state )
    {
        // Dummy
    }
}

/**
 * Tests ezcPersistentObject implementation with a ctor.
 * 
 * @package PersistentObject
 * @subpackage Tests
 */
class ezcPersistentTestObjectCtor implements ezcPersistentObject
{
    public function __construct()
    {
        // Dummy
    }

    public function getState()
    {
        // Dummy
    }

    public function setState( array $state )
    {
        // Dummy
    }
}

/**
 * Tests the ezcPersistentObjectTest class.
 *
 * @package PersistentObject
 * @subpackage Tests
 */
class ezcPersistentObjectTest extends ezcTestCase
{
    public static function suite()
    {
        return new PHPUnit_Framework_TestSuite( self::class );
    }

    public function testWithoutCtor()
    {
        $foo = new ezcPersistentTestObjectNoCtor();
    }

    public function testWithCtor()
    {
        $foo = new ezcPersistentTestObjectCtor();
    }
}

?>
