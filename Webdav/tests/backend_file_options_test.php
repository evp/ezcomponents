<?php
/**
 * File containing the ezcWebdavFileBackendOptionsTestCase class.
 * 
 * @package Webdav
 * @version 1.1.4
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @subpackage Test
 */

require_once __DIR__ . '/property_test.php';

/**
 * Test case for the ezcWebdavFileBackendOptions class.
 * 
 * @package Webdav
 * @version 1.1.4
 * @subpackage Test
 */
class ezcWebdavFileBackendOptionsTestCase extends ezcWebdavPropertyTestCase
{
    public static function suite()
    {
		return new PHPUnit_Framework_TestSuite( self::class );
    }

    protected function setUp()
    {
        $this->className = 'ezcWebdavFileBackendOptions';
        $this->defaultValues = ['noLock'                => false, 'waitForLock'           => 200000, 'lockFileName'          => '.ezc_lock', 'propertyStoragePath'   => '.ezc', 'directoryMode'         => 0755, 'fileMode'              => 0644, 'useMimeExts'           => true, 'hideDotFiles'          => true];
        $this->workingValues = ['noLock'                => [true, false], 'waitForLock'           => [0, 100000], 'lockFileName'          => ['.foo', 'bar'], 'propertyStoragePath'   => ['.foo', 'bar'], 'directoryMode'         => [0, 100], 'fileMode'              => [0, 100], 'useMimeExts'           => [true, false], 'hideDotFiles'          => [true, false]];
        $this->failingValues = ['noLock'                => [23, 23.34, 'foo', [], new stdClass()], 'waitForLock'           => [23.34, 'foo', [], false, new stdClass()], 'lockFileName'          => [23, 23.34, [], false, new stdClass()], 'propertyStoragePath'   => [23, 23.34, [], false, new stdClass()], 'directoryMode'         => [23.34, 'foo', [], false, new stdClass()], 'fileMode'              => [23.34, 'foo', [], false, new stdClass()], 'useMimeExts'           => [23, 23.34, 'foo', [], new stdClass()], 'hideDotFiles'          => [23, 23.34, 'foo', [], new stdClass()]];
    }

    public function testCtorSuccess()
    {
        $class = new ReflectionClass( $this->className );
        $object = $class->newInstance();

        $this->assertPropertyValues( $object, $this->defaultValues );
    }
}

?>
