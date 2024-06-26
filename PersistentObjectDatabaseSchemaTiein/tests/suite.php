<?php
/**
 * ezcPersistentObjectDatabaseSchemaTieinSuite
 * 
 * @package PersistentObjectDatabaseSchemaTiein
 * @subpackage Tests
 * @version 1.3
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

require_once __DIR__ . "/generator_test.php";

require_once __DIR__ . "/template_functions_test.php";
require_once __DIR__ . "/template_writer_options_test.php";
require_once __DIR__ . "/template_writer_test.php";

/**
 * Test suite for PersistentObjectDatabaseSchemaTiein package.
 * 
 * @package PersistentObjectDatabaseSchemaTiein
 * @subpackage Tests
 */
class ezcPersistentObjectDatabaseSchemaTieinSuite extends PHPUnit_Framework_TestSuite
{
	public function __construct()
	{
		parent::__construct();
        $this->setName( "PersistentObjectDatabaseSchemaTiein" );
		$this->addTest( ezcPersistentObjectSchemaGeneratorTest::suite() );
		$this->addTest( ezcPersistentObjectSchemaTemplateFunctionsTest::suite() );
		$this->addTest( ezcPersistentObjectTemplateSchemaWriterOptionsTest::suite() );
		$this->addTest( ezcPersistentObjectTemplateSchemaWriterTest::suite() );
	}

    public static function suite()
    {
        return new ezcPersistentObjectDatabaseSchemaTieinSuite( "ezcPersistentObjectDatabaseSchemaTieinSuite" );
    }
}
?>
