<?php
/**
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @version 1.7.1
 * @filesource
 * @package PersistentObject
 * @subpackage Tests
 */

require_once __DIR__ . '/../data/persistent_test_object.php';
require_once __DIR__ . '/../data/persistent_test_object_no_id.php';
require_once __DIR__ . '/../data/persistent_test_object_converter.php';
require_once __DIR__ . '/../data/persistent_test_object_invalid_state.php';

require_once __DIR__ . '/../data/relation_test_address.php';
require_once __DIR__ . '/../data/relation_test_person.php';
require_once __DIR__ . '/../data/relation_test_birthday.php';
require_once __DIR__ . '/../data/relation_test_employer.php';

require_once __DIR__ . '/../data/multi_relation_test_person.php';

/**
 * Tests the code manager.
 *
 * @package PersistentObject
 * @subpackage Tests
 */
class ezcPersistentSessionTest extends ezcTestCase
{
    protected $session = null;
    protected $hasTables = false;

    protected function setUp()
    {
        try
        {
            $db = ezcDbInstance::get();
        }
        catch ( Exception $e )
        {
            $this->markTestSkipped( 'There was no database configured' );
        }

        PersistentTestObject::setupTable();
        PersistentTestObject::insertCleanData();
        // Uncomment to store schema.
        // PersistentTestObject::saveSqlSchemas();
        $this->session = new ezcPersistentSession(
            ezcDbInstance::get(),
            new ezcPersistentCodeManager( __DIR__ . "/../data/" )
        );
    }

    protected function tearDown()
    {
        PersistentTestObject::cleanup();
    }

    public static function suite()
    {
        return new PHPUnit_Framework_TestSuite( 'ezcPersistentSessionTest' );
    }
}

?>
