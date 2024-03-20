<?php
/**
 * File containing the ezcWebdavRequestPropertyBehaviourContentTest class.
 * 
 * @package Webdav
 * @version 1.1.4
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @subpackage Test
 */

require_once __DIR__ . '/property_test.php';

/**
 * Test case for the ezcWebdavRequestPropertyBehaviourContent class.
 * 
 * @package Webdav
 * @version 1.1.4
 * @subpackage Test
 */
class ezcWebdavRequestPropertyBehaviourContentTest extends ezcWebdavPropertyTestCase
{
    public static function suite()
    {
		return new PHPUnit_Framework_TestSuite( self::class );
    }

    protected function setUp()
    {
        $this->className = 'ezcWebdavRequestPropertyBehaviourContent';
        $this->defaultValues = ['keepAlive' => null, 'omit'      => false];
        $this->workingValues = ['keepAlive' => [['http://example.com', 'http://example.com/test'], ezcWebdavRequestPropertyBehaviourContent::ALL, null], 'omit' => [true, false]];
        $this->failingValues = ['keepAlive' => [23, 23.34, 'foo', true, false, new stdClass()], 'omit' => [23, 23.34, 'foo', new stdClass(), [23, 42]]];
    }

    public function testCtorSuccess()
    {
        $class = new ReflectionClass( $this->className );
        $object = $class->newInstance();
        $this->assertPropertyValues( $object, $this->defaultValues );
    }
}

?>
