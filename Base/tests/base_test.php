<?php
/**
 * @package Base
 * @subpackage Tests
 * @version 1.8
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */
/**
 * @package Base
 * @subpackage Tests
 */
class ezcBaseTest extends ezcTestCase
{
    /*
     * For use with the method testInvalidClass().
     */
    private $errorMessage = null;

    public function testConfigExceptionUnknownSetting()
    {
        try
        {
            throw new ezcBaseSettingNotFoundException( 'broken' );
        }
        catch ( ezcBaseSettingNotFoundException $e )
        {
            $this->assertEquals( "The setting 'broken' is not a valid configuration setting.", $e->getMessage() );
        }
    }

    public function testConfigExceptionOutOfRange1()
    {
        try
        {
            throw new ezcBaseSettingValueException( 'broken', 42 );
        }
        catch ( ezcBaseSettingValueException $e )
        {
            $this->assertEquals( "The value '42' that you were trying to assign to setting 'broken' is invalid.", $e->getMessage() );
        }
    }

    public function testConfigExceptionOutOfRange2()
    {
        try
        {
            throw new ezcBaseSettingValueException( 'broken', 42, "int, 40 - 48" );
        }
        catch ( ezcBaseSettingValueException $e )
        {
            $this->assertEquals( "The value '42' that you were trying to assign to setting 'broken' is invalid. Allowed values are: int, 40 - 48", $e->getMessage() );
        }
    }

    public function testConfigExceptionOutOfRange3()
    {
        try
        {
            throw new ezcBaseSettingValueException( 'broken', [1, 1, 3, 4, 5], 'int' );
        }
        catch ( ezcBaseSettingValueException $e )
        {
            $this->assertEquals( "The value 'a:5:{i:0;i:1;i:1;i:1;i:2;i:3;i:3;i:4;i:4;i:5;}' that you were trying to assign to setting 'broken' is invalid. Allowed values are: int", $e->getMessage() );
        }
    }

    public function testFileIoException1()
    {
        try
        {
            throw new ezcBaseFileIoException( 'testfile.php', ezcBaseFileException::READ );
        }
        catch ( ezcBaseFileIoException $e )
        {
            $this->assertEquals( "An error occurred while reading from 'testfile.php'.", $e->getMessage() );
        }
    }

    public function testFileIoException2()
    {
        try
        {
            throw new ezcBaseFileIoException( 'testfile.php', ezcBaseFileException::WRITE );
        }
        catch ( ezcBaseFileIoException $e )
        {
            $this->assertEquals( "An error occurred while writing to 'testfile.php'.", $e->getMessage() );
        }
    }

    public function testFileIoException3()
    {
        try
        {
            throw new ezcBaseFileIoException( 'testfile.php', ezcBaseFileException::WRITE, "Extra extra" );
        }
        catch ( ezcBaseFileIoException $e )
        {
            $this->assertEquals( "An error occurred while writing to 'testfile.php'. (Extra extra)", $e->getMessage() );
        }
    }

    public function testFileNotFoundException1()
    {
        try
        {
            throw new ezcBaseFileNotFoundException( 'testfile.php' );
        }
        catch ( ezcBaseFileNotFoundException $e )
        {
            $this->assertEquals( "The file 'testfile.php' could not be found.", $e->getMessage() );
        }
    }

    public function testFileNotFoundException2()
    {
        try
        {
            throw new ezcBaseFileNotFoundException( 'testfile.php', 'INI' );
        }
        catch ( ezcBaseFileNotFoundException $e )
        {
            $this->assertEquals( "The INI file 'testfile.php' could not be found.", $e->getMessage() );
        }
    }

    public function testFileNotFoundException3()
    {
        try
        {
            throw new ezcBaseFileNotFoundException( 'testfile.php', 'INI', "Extra extra" );
        }
        catch ( ezcBaseFileNotFoundException $e )
        {
            $this->assertEquals( "The INI file 'testfile.php' could not be found. (Extra extra)", $e->getMessage() );
        }
    }

    public function testFilePermissionException1()
    {
        try
        {
            throw new ezcBaseFilePermissionException( 'testfile.php', ezcBaseFileException::READ );
        }
        catch ( ezcBaseFilePermissionException $e )
        {
            $this->assertEquals( "The file 'testfile.php' can not be opened for reading.", $e->getMessage() );
        }
    }

    public function testFilePermissionException2()
    {
        try
        {
            throw new ezcBaseFilePermissionException( 'testfile.php', ezcBaseFileException::WRITE );
        }
        catch ( ezcBaseFileException $e )
        {
            $this->assertEquals( "The file 'testfile.php' can not be opened for writing.", $e->getMessage() );
        }
    }

    public function testFilePermissionException3()
    {
        try
        {
            throw new ezcBaseFilePermissionException( 'testfile.php', ezcBaseFileException::EXECUTE );
        }
        catch ( ezcBaseException $e )
        {
            $this->assertEquals( "The file 'testfile.php' can not be executed.", $e->getMessage() );
        }
    }

    public function testFilePermissionException4()
    {
        try
        {
            throw new ezcBaseFilePermissionException( 'testfile.php', ezcBaseFilePermissionException::CHANGE, "Extra extra" );
        }
        catch ( ezcBaseException $e )
        {
            $this->assertEquals( "The permissions for 'testfile.php' can not be changed. (Extra extra)", $e->getMessage() );
        }
    }

    public function testFilePermissionException5()
    {
        try
        {
            throw new ezcBaseFilePermissionException( 'testfile.php', ezcBaseFilePermissionException::READ | ezcBaseFilePermissionException::WRITE, "Extra extra" );
        }
        catch ( ezcBaseException $e )
        {
            $this->assertEquals( "The file 'testfile.php' can not be opened for reading and writing. (Extra extra)", $e->getMessage() );
        }
    }

    public function testFilePermissionException6()
    {
        try
        {
            throw new ezcBaseFilePermissionException( 'testfile.php', ezcBaseFilePermissionException::REMOVE, "Extra extra" );
        }
        catch ( ezcBaseException $e )
        {
            $this->assertEquals( "The file 'testfile.php' can not be removed. (Extra extra)", $e->getMessage() );
        }
    }

    public function testPropertyNotFoundException()
    {
        try
        {
            throw new ezcBasePropertyNotFoundException( 'broken' );
        }
        catch ( ezcBasePropertyNotFoundException $e )
        {
            $this->assertEquals( "No such property name 'broken'.", $e->getMessage() );
        }
    }

    public function testPropertyPermissionException1()
    {
        try
        {
            throw new ezcBasePropertyPermissionException( 'broken', ezcBasePropertyPermissionException::READ );
        }
        catch ( ezcBaseException $e )
        {
            $this->assertEquals( "The property 'broken' is read-only.", $e->getMessage() );
        }
    }

    public function testPropertyPermissionException2()
    {
        try
        {
            throw new ezcBasePropertyPermissionException( 'broken', ezcBasePropertyPermissionException::WRITE );
        }
        catch ( ezcBaseException $e )
        {
            $this->assertEquals( "The property 'broken' is write-only.", $e->getMessage() );
        }
    }

    public function testBaseValue1()
    {
        try
        {
            throw new ezcBaseValueException( 'broken', [42] );
        }
        catch ( ezcBaseValueException $e )
        {
            $this->assertEquals( "The value 'a:1:{i:0;i:42;}' that you were trying to assign to setting 'broken' is invalid.", $e->getMessage() );
        }
    }

    public function testBaseValue2()
    {
        try
        {
            throw new ezcBaseValueException( 'broken', "string", "strings" );
        }
        catch ( ezcBaseValueException $e )
        {
            $this->assertEquals( "The value 'string' that you were trying to assign to setting 'broken' is invalid. Allowed values are: strings.", $e->getMessage() );
            $this->assertEquals( "The value 'string' that you were trying to assign to setting 'broken' is invalid. Allowed values are: strings.", $e->originalMessage );
        }
    }

    public function testExtraDirNotFoundException()
    {
        try
        {
            ezcBase::addClassRepository( 'wrongDir' );
        }
        catch ( ezcBaseFileNotFoundException $e )
        {
            $this->assertEquals( "The base directory file 'wrongDir' could not be found.", $e->getMessage() );
        }
    }

    public function testExtraDirBaseNotFoundException()
    {
        try
        {
            ezcBase::addClassRepository( '.', './wrongAutoloadDir' );
        }
        catch ( ezcBaseFileNotFoundException $e )
        {
            $this->assertEquals( "The autoload directory file './wrongAutoloadDir' could not be found.", $e->getMessage() );
        }
    }
    
    public function testBaseAddAndGetAutoloadDirs1()
    {
        ezcBase::addClassRepository( '.' );
        $resultArray = ezcBase::getRepositoryDirectories();

        if ( count( $resultArray ) != 2 ) 
        {
            $this->fail( "Duplicating or missing extra autoload dirs while adding." );
        }

        if ( !isset( $resultArray['ezc'] ) ) 
        {
           $this->fail( "No packageDir found in result of getRepositoryDirectories()" );
        }

        if ( !isset( $resultArray[0] ) || $resultArray[0]->basePath != getcwd() )
        {
            $this->fail( "Extra base dir '{$resultArray[0]->basePath}' is added incorrectly" );
        }

        if ( !isset( $resultArray[0] ) || $resultArray[0]->autoloadPath != getcwd() . '/autoload' )
        {
            $this->fail( "Extra autoload dir '{$resultArray[0]->autoloadPath}' is added incorrectly" );
        }
    }

    // this test is sorta obsolete, but we keep it around for good measure
    public function testBaseAddAndGetAutoloadDirs2()
    {
        ezcBase::addClassRepository( '.', './autoload' );
        ezcBase::addClassRepository( './Base/tests/test_repository', './Base/tests/test_repository/autoload_files' );
        ezcBase::addClassRepository( './Base/tests/test_repository', './Base/tests/test_repository/autoload_files' );
        $resultArray = ezcBase::getRepositoryDirectories();

        if ( count( $resultArray ) != 5 ) 
        {
            $this->fail( "Duplicating or missing extra autoload dirs while adding." );
        }

        if ( !isset( $resultArray['ezc'] ) ) 
        {
           $this->fail( "No packageDir found in result of getRepositoryDirectories()" );
        }

        if ( !isset( $resultArray[2] ) || $resultArray[2]->autoloadPath != getcwd() . '/Base/tests/test_repository/autoload_files' )
        {
            $this->fail( "Extra autoload dir '{$resultArray[2]->autoloadPath}' is added incorrectly" );
        }

        self::assertEquals( true, class_exists( 'trBasetestClass', true ) );
        self::assertEquals( true, class_exists( 'trBasetestClass2', true ) );

        try
        {
            self::assertEquals( false, class_exists( 'trBasetestClass3', true ) );
            self::fail( 'The expected exception was not thrown.' );
        }
        catch ( ezcBaseAutoloadException $e )
        {
            $cwd = getcwd();
            self::assertEquals( "Could not find a class to file mapping for 'trBasetestClass3'. Searched for basetest_class3_autoload.php, basetest_autoload.php, autoload.php in: $cwd/autoload, $cwd/autoload, $cwd/autoload, $cwd/Base/tests/test_repository/autoload_files, $cwd/Base/tests/test_repository/autoload_files", $e->getMessage() );
        }

        self::assertEquals( true, class_exists( 'trBasetestLongClass', true ) );

        try
        {
            class_exists( 'trBasetestClass4', true );
            self::fail( 'The expected exception was not thrown.' );
        }
        catch ( ezcBaseFileNotFoundException $e )
        {
            self::assertEquals( "The file './Base/tests/test_repository/TestClasses/base_test_class_number_four.php' could not be found.", $e->getMessage() );
        }
    }

    public function testBaseAddAndGetAutoloadDirs3()
    {
        ezcBase::addClassRepository( './Base/tests/extra_repository', null, 'ext' );

        $resultArray = ezcBase::getRepositoryDirectories();
        self::assertEquals( true, array_key_exists( 'ezc', $resultArray ) );
        self::assertEquals( true, array_key_exists( 'ext', $resultArray ) );

        self::assertEquals( true, class_exists( 'extTranslationTest', true ) );
        self::assertEquals( true, class_exists( 'ezcTranslationTsBackend', true ) );
    }

    public function testBaseAddAndGetAutoloadDirs4()
    {
        ezcBase::addClassRepository( './Base/tests/test_repository', './Base/tests/test_repository/autoload_files', 'tr' );

        try
        {
            ezcBase::addClassRepository( './Base/tests/test_repository', './Base/tests/test_repository/autoload_files', 'tr' );
        }
        catch ( ezcBaseDoubleClassRepositoryPrefixException $e )
        {
            self::assertEquals( "The class repository in './Base/tests/test_repository' (with autoload dir './Base/tests/test_repository/autoload_files') can not be added because another class repository already uses the prefix 'tr'.", $e->getMessage() );
        }

        $resultArray = ezcBase::getRepositoryDirectories();
        self::assertEquals( 7, count( $resultArray ) );

        self::assertEquals( true, array_key_exists( 'ezc', $resultArray ) );
        self::assertEquals( true, array_key_exists( 'tr', $resultArray ) );

        self::assertEquals( getcwd() . '/Base/tests/test_repository', $resultArray['tr']->basePath );
        self::assertEquals( getcwd() . '/Base/tests/test_repository/autoload_files', $resultArray['tr']->autoloadPath );
    }

    public function testNoPrefixAutoload2()
    {
        ezcBase::addClassRepository( './Base/tests/issue15896' );
        __autoload( 'ab' );
    }

    public function testCheckDependencyExtension()
    {
        ezcBase::checkDependency( 'Tester', ezcBase::DEP_PHP_EXTENSION, 'standard' );
    }

    public function testCheckDependencyVersion()
    {
        ezcBase::checkDependency( 'Tester', ezcBase::DEP_PHP_VERSION, '5.1.1' );
    }

    public function testInvalidClass()
    {
        try
        {
            self::assertEquals( false, class_exists( 'ezcNoSuchClass', true ) );
            self::fail( 'The expected exception was not thrown.' );
        }
        catch ( ezcBaseAutoloadException $e )
        {
            $cwd = getcwd();
            self::assertEquals( "Could not find a class to file mapping for 'ezcNoSuchClass'. Searched for no_such_autoload.php, no_autoload.php, autoload.php in: $cwd/autoload, $cwd/autoload, $cwd/autoload, $cwd/Base/tests/test_repository/autoload_files, $cwd/Base/tests/test_repository/autoload_files, $cwd/Base/tests/extra_repository/autoload, $cwd/Base/tests/test_repository/autoload_files, $cwd/Base/tests/test_repository/autoload_files, $cwd/Base/tests/issue15896/autoload", $e->getMessage() );
        }
    }

    public function testDebug()
    {
        try
        {
            class_exists( 'ezcTestingOne' );
            self::fail( "There should have been an exception" );
        }
        catch ( ezcBaseAutoloadException $e )
        {
        }
    }

    public function testNoDebug()
    {
        try
        {
            $options = new ezcBaseAutoloadOptions;
            $options->debug = false;
            ezcBase::setOptions( $options );

            class_exists( 'ezcTestingOne' );
        }
        catch ( Exception $e )
        {
            self::fail( "There should not have been an exception" );
        }
    }

    public function testGetInstallationPath()
    {
        $path = ezcBase::getInstallationPath();
        $pathParts = explode( DIRECTORY_SEPARATOR, $path );
        self::assertEquals( ['trunk', ''], array_splice( $pathParts, -2 ) );
        self::assertEquals( DIRECTORY_SEPARATOR, substr( $path, -1 ) );
    }

    public function testSetInvalidRunMode()
    {
        try
        {
            ezcBase::setRunMode( -3 );
            self::fail( "Expected exception not thrown." );
        }
        catch ( ezcBaseValueException $e )
        {
            self::assertEquals( "The value '-3' that you were trying to assign to setting 'runMode' is invalid. Allowed values are: ezcBase::MODE_PRODUCTION or ezcBase::MODE_DEVELOPMENT.", $e->getMessage() );
        }
    }

    public function testSetGetRunMode()
    {
        self::assertEquals( ezcBase::MODE_DEVELOPMENT, ezcBase::getRunMode() );
        self::assertEquals( true, ezcBase::inDevMode() );

        ezcBase::setRunMode( ezcBase::MODE_PRODUCTION );
        self::assertEquals( ezcBase::MODE_PRODUCTION, ezcBase::getRunMode() );
        self::assertEquals( false, ezcBase::inDevMode() );

        ezcBase::setRunMode( ezcBase::MODE_DEVELOPMENT );
        self::assertEquals( ezcBase::MODE_DEVELOPMENT, ezcBase::getRunMode() );
        self::assertEquals( true, ezcBase::inDevMode() );
    }

    public function testGetInstallMethod()
    {
        self::assertEquals( 'devel', ezcBase::getInstallMethod() );
    }

    public function setup()
    {
        $options = new ezcBaseAutoloadOptions;
        $options->debug = true;
        ezcBase::setOptions( $options );
    }

    public function teardown()
    {
        $options = new ezcBaseAutoloadOptions;
        $options->debug = true;
        ezcBase::setOptions( $options );
    }

    public static function suite()
    {
        return new PHPUnit_Framework_TestSuite("ezcBaseTest");
    }
}
?>
