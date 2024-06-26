<?php
/**
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @version 1.3.5
 * @filesource
 * @package Configuration
 * @subpackage Tests
 */

/**
 * @package Configuration
 * @subpackage Tests
 */
class ezcConfigurationTest extends ezcTestCase
{
    public function testAllSettings()
    {
        $settings = ['TheOnlyGroup' => ['Setting1' => true, 'SettingNoComment' => 42, 'MultiRow' => false]];
        $comments = ['TheOnlyGroup' => ['#' => "Just one group", 'Setting1' => " This setting sucks", 'MultiRow' => " Multi\n row\n comment"]];
        $return = new ezcConfiguration( $settings, $comments );
        $this->assertEquals( $settings, $return->getAllSettings() );
    }

    public function testAllComments()
    {
        $settings = ['TheOnlyGroup' => ['Setting1' => true, 'SettingNoComment' => 42, 'MultiRow' => false]];
        $comments = ['TheOnlyGroup' => ['#' => "Just one group", 'Setting1' => " This setting sucks", 'MultiRow' => " Multi\n row\n comment"]];
        $return = new ezcConfiguration( $settings, $comments );
        $this->assertEquals( $comments, $return->getAllComments() );
    }

    public function testHasSetting1()
    {
        $settings = [];
        $comments = [];
        $return = new ezcConfiguration( $settings, $comments );
        $this->assertEquals( false, $return->hasSetting( 'NonExistingGroup', 'NonExistent' ) );
    }

    public function testHasSetting2()
    {
        $settings = ['TheOnlyGroup' => ['Setting1' => true, 'SettingNoComment' => 42, 'MultiRow' => false]];
        $comments = [];
        $return = new ezcConfiguration( $settings, $comments );
        $this->assertEquals( true, $return->hasSetting( 'TheOnlyGroup', 'Setting1' ) );
        $this->assertEquals( true, $return->hasSetting( 'TheOnlyGroup', 'SettingNoComment' ) );
        $this->assertEquals( true, $return->hasSetting( 'TheOnlyGroup', 'MultiRow' ) );
        $this->assertEquals( false, $return->hasSetting( 'TheOnlyGroup', 'NonExistent' ) );
        $this->assertEquals( false, $return->hasSetting( 'NonExistingGroup', 'Setting1' ) );
        $this->assertEquals( false, $return->hasSetting( 'NonExistingGroup', 'NonExistent' ) );
    }

    public function testHasSetting3()
    {
        $settings = ['3D' => ['Decimal' => [42, 0], 'Array' =>  ['Decimal' => ['a' => 42, 'b' => 0], 'Mixed' => ['b' => false, 2 => "Derick \"Tiger\" Rethans"]]]];
        $comments = [];
        $return = new ezcConfiguration( $settings, $comments );
        $this->assertEquals( true, $return->hasSetting( '3D', 'Decimal' ) );
        $this->assertEquals( true, $return->hasSetting( '3D', 'Array' ) );
        $this->assertEquals( false, $return->hasSetting( '3D', 'Array[Mixed]' ) );
        $this->assertEquals( false, $return->hasSetting( '3D', 'NonExistent' ) );
    }

    public function testSettingBrokenSettingName()
    {
        $settings = ['TheOnlyGroup' => ['Setting1' => true]];
        $comments = [];
        $return = new ezcConfiguration( $settings, $comments );
        try
        {
            $setting = $return->getSetting( 'TheOnlyGroup', ['Setting1'] );
            $this->fail( "Expected exception not thrown" );
        }
        catch ( ezcConfigurationSettingnameNotStringException $e )
        {
            $this->assertEquals( "The setting name that was passed is not a string, but an 'array'.", $e->getMessage() );
        }
    }

    public function testSetting1()
    {
        $settings = ['TheOnlyGroup' => ['Setting1' => true, 'SettingNoComment' => 42, 'MultiRow' => false]];
        $comments = [];
        $return = new ezcConfiguration( $settings, $comments );
        $this->assertEquals( true, $return->getSetting( 'TheOnlyGroup', 'Setting1' ) );
        $this->assertEquals( 42, $return->getSetting( 'TheOnlyGroup', 'SettingNoComment' ) );
        $this->assertEquals( false, $return->getSetting( 'TheOnlyGroup', 'MultiRow' ) );
    }

    public function testSetting2()
    {
        $settings = ['TheOnlyGroup' => []];
        $comments = [];
        $configuration = new ezcConfiguration( $settings, $comments );

        try
        {
            $dummy = $configuration->getSetting( 'NonExistingGroup', 'Dummy' );
            $this->fail( "Expected exception not thrown" );
        }
        catch ( ezcConfigurationUnknownGroupException $e )
        {
            $this->assertEquals( "The settings group 'NonExistingGroup' does not exist.", $e->getMessage() );
        }
    }

    public function testSetting3()
    {
        $settings = ['TheOnlyGroup' => []];
        $comments = [];
        $configuration = new ezcConfiguration( $settings, $comments );

        try
        {
            $dummy = $configuration->getSetting( 'TheOnlyGroup', 'NonExistent' );
            $this->fail( "Expected exception not thrown" );
        }
        catch ( ezcConfigurationUnknownSettingException $e )
        {
            $this->assertEquals( "The setting 'TheOnlyGroup', 'NonExistent' does not exist.", $e->getMessage() );
        }
    }

    public function testBoolSetting()
    {
        $settings = ['Types' => ['Bool' => true, 'Float' => 3.14, 'Int' => 42, 'String' => "Components", 'Array' => [1 => 'Een', 2 => 'Twee']]];
        $comments = [];
        $configuration = new ezcConfiguration( $settings, $comments );

        $bool = $configuration->getBoolSetting( 'Types', 'Bool' );
        $this->assertEquals( true, $bool );
        try
        {
            $configuration->getBoolSetting( 'Types', 'Float' );
            $configuration->getBoolSetting( 'Types', 'Int' );
            $configuration->getBoolSetting( 'Types', 'String' );
            $configuration->getBoolSetting( 'Types', 'Array' );
            $this->fail( "Expected exception not thrown" );
        }
        catch ( ezcConfigurationSettingWrongTypeException $e )
        {
            $this->assertEquals( "The expected type for the setting 'Types', 'Float' is 'boolean'. The setting was of type 'double'.", $e->getMessage() );
        }
    }

    public function testNumberSetting1()
    {
        $settings = ['Types' => ['Bool' => true, 'Float' => 3.14, 'Int' => 42, 'String' => "Components", 'Array' => [1 => 'Een', 2 => 'Twee']]];
        $comments = [];
        $configuration = new ezcConfiguration( $settings, $comments );

        $float = $configuration->getNumberSetting( 'Types', 'Float' );
        $this->assertEquals( 3.14, $float );
        try
        {
            $configuration->getNumberSetting( 'Types', 'Bool' );
            $configuration->getNumberSetting( 'Types', 'Int' );
            $configuration->getNumberSetting( 'Types', 'String' );
            $configuration->getNumberSetting( 'Types', 'Array' );
            $this->fail( "Expected exception not thrown" );
        }
        catch ( ezcConfigurationSettingWrongTypeException $e )
        {
            $this->assertEquals( "The expected type for the setting 'Types', 'Bool' is 'double or integer'. The setting was of type 'boolean'.", $e->getMessage() );
        }
    }

    public function testNumberSetting2()
    {
        $settings = ['Types' => ['Bool' => true, 'Float' => 3.14, 'Int' => 42, 'String' => "Components", 'Array' => [1 => 'Een', 2 => 'Twee']]];
        $comments = [];
        $configuration = new ezcConfiguration( $settings, $comments );

        $number = $configuration->getNumberSetting( 'Types', 'Int' );
        $this->assertEquals( 42, $number );
        try
        {
            $configuration->getNumberSetting( 'Types', 'String' );
            $configuration->getNumberSetting( 'Types', 'Bool' );
            $configuration->getNumberSetting( 'Types', 'Float' );
            $configuration->getNumberSetting( 'Types', 'Array' );
            $this->fail( "Expected exception not thrown" );
        }
        catch ( ezcConfigurationSettingWrongTypeException $e )
        {
            $this->assertEquals( "The expected type for the setting 'Types', 'String' is 'double or integer'. The setting was of type 'string'.", $e->getMessage() );
        }
    }

    public function testStringSetting()
    {
        $settings = ['Types' => ['Bool' => true, 'Float' => 3.14, 'Int' => 42, 'String' => "Components", 'Array' => [1 => 'Een', 2 => 'Twee']]];
        $comments = [];
        $configuration = new ezcConfiguration( $settings, $comments );

        $string = $configuration->getStringSetting( 'Types', 'String' );
        $this->assertEquals( 'Components', $string );
        try
        {
            $configuration->getStringSetting( 'Types', 'Array' );
            $configuration->getStringSetting( 'Types', 'Bool' );
            $configuration->getStringSetting( 'Types', 'Float' );
            $configuration->getStringSetting( 'Types', 'Int' );
            $this->fail( "Expected exception not thrown" );
        }
        catch ( ezcConfigurationSettingWrongTypeException $e )
        {
            $this->assertEquals( "The expected type for the setting 'Types', 'Array' is 'string'. The setting was of type 'array'.", $e->getMessage() );
        }
    }

    public function testArraySetting()
    {
        $settings = ['Types' => ['Bool' => true, 'Float' => 3.14, 'Int' => 42, 'String' => "Components", 'Array' => [1 => 'Een', 2 => 'Twee']]];
        $comments = [];
        $configuration = new ezcConfiguration( $settings, $comments );

        $array = $configuration->getArraySetting( 'Types', 'Array' );
        $this->assertEquals( [1 => 'Een', 2 => 'Twee'], $array );
        try
        {
            $configuration->getArraySetting( 'Types', 'Bool' );
            $configuration->getArraySetting( 'Types', 'Float' );
            $configuration->getArraySetting( 'Types', 'Int' );
            $configuration->getArraySetting( 'Types', 'String' );
            $this->fail( "Expected exception not thrown" );
        }
        catch ( ezcConfigurationSettingWrongTypeException $e )
        {
            $this->assertEquals( "The expected type for the setting 'Types', 'Bool' is 'array'. The setting was of type 'boolean'.", $e->getMessage() );
        }
    }

    public function testComment()
    {
        $settings = ['TheOnlyGroup' => ['Setting1' => true, 'SettingNoComment' => 42]];
        $comments = ['TheOnlyGroup' => ['Setting1' => 'Comment for setting 1']];
        $return = new ezcConfiguration( $settings, $comments );
        $this->assertEquals( 'Comment for setting 1', $return->getComment( 'TheOnlyGroup', 'Setting1' ) );
        $this->assertEquals( false, $return->getComment( 'TheOnlyGroup', 'SettingNoComment' ) );
    }

    public function testComment2()
    {
        $settings = ['TheOnlyGroup' => []];
        $comments = [];
        $configuration = new ezcConfiguration( $settings, $comments );

        try
        {
            $dummy = $configuration->getComment( 'NonExistingGroup', 'Dummy' );
            $this->fail( "Expected exception not thrown" );
        }
        catch ( ezcConfigurationUnknownGroupException $e )
        {
            $this->assertEquals( "The settings group 'NonExistingGroup' does not exist.", $e->getMessage() );
        }
    }

    public function testComment3()
    {
        $settings = ['TheOnlyGroup' => []];
        $comments = [];
        $configuration = new ezcConfiguration( $settings, $comments );

        try
        {
            $dummy = $configuration->getComment( 'TheOnlyGroup', 'NonExistent' );
            $this->fail( "Expected exception not thrown" );
        }
        catch ( ezcConfigurationUnknownSettingException $e )
        {
            $this->assertEquals( "The setting 'TheOnlyGroup', 'NonExistent' does not exist.", $e->getMessage() );
        }
    }

    public function testSetSettingOverwrite()
    {
        $settings = ['TheOnlyGroup' => ['Existing1' => true, 'Existing2' => true]];
        $comments = [];
        $configuration = new ezcConfiguration( $settings, $comments );
        $this->assertSame( false, $this->readAttribute( $configuration, 'isModified' ) );

        $configuration->setSetting( 'TheOnlyGroup', 'Existing1', 'yes' );
        $configuration->setSetting( 'TheOnlyGroup', 'Existing2', 'yes', 'With comment' );
        $this->assertSame( true, $this->readAttribute( $configuration, 'isModified' ) );
        $this->assertEquals( 'yes', $configuration->getSetting( 'TheOnlyGroup', 'Existing1' ) );
        $this->assertEquals( 'yes', $configuration->getSetting( 'TheOnlyGroup', 'Existing2' ) );
        $this->assertEquals( false, $configuration->getComment( 'TheOnlyGroup', 'Existing1' ) );
        $this->assertEquals( 'With comment', $configuration->getComment( 'TheOnlyGroup', 'Existing2' ) );
    }

    public function testSetSettingNew()
    {
        $settings = ['TheOnlyGroup' => ['Existing' => true]];
        $comments = [];
        $configuration = new ezcConfiguration( $settings, $comments );
        $this->assertSame( false, $this->readAttribute( $configuration, 'isModified' ) );

        $configuration->setSetting( 'TheOnlyGroup', 'New1', 42 );
        $configuration->setSetting( 'TheOnlyGroup', 'New2', 43, 'With comment' );
        $this->assertSame( true, $this->readAttribute( $configuration, 'isModified' ) );
        $this->assertEquals( 42, $configuration->getSetting( 'TheOnlyGroup', 'New1' ) );
        $this->assertEquals( 43, $configuration->getSetting( 'TheOnlyGroup', 'New2' ) );
        $this->assertEquals( false, $configuration->getComment( 'TheOnlyGroup', 'New1' ) );
        $this->assertEquals( 'With comment', $configuration->getComment( 'TheOnlyGroup', 'New2' ) );
    }

    public function testSettingRemove1()
    {
        $settings = ['TheOnlyGroup' => ['Setting1' => true]];
        $comments = [];
        $configuration = new ezcConfiguration( $settings, $comments );
        $this->assertSame( false, $this->readAttribute( $configuration, 'isModified' ) );

        $configuration->removeSetting( 'TheOnlyGroup', 'Setting1' );
        $this->assertSame( true, $this->readAttribute( $configuration, 'isModified' ) );
        $this->assertEquals( false, $configuration->hasSetting( 'TheOnlyGroup', 'Setting1' ) );
    }

    public function testSettingRemove2()
    {
        $settings = ['TheOnlyGroup' => []];
        $comments = [];
        $configuration = new ezcConfiguration( $settings, $comments );

        try
        {
            $dummy = $configuration->removeSetting( 'NonExistingGroup', 'Dummy' );
            $this->fail( "Expected exception not thrown" );
        }
        catch ( ezcConfigurationUnknownGroupException $e )
        {
            $this->assertEquals( "The settings group 'NonExistingGroup' does not exist.", $e->getMessage() );
        }
    }

    public function testSettingRemove3()
    {
        $settings = ['TheOnlyGroup' => []];
        $comments = [];
        $configuration = new ezcConfiguration( $settings, $comments );

        try
        {
            $dummy = $configuration->removeSetting( 'TheOnlyGroup', 'NonExistent' );
            $this->fail( "Expected exception not thrown" );
        }
        catch ( ezcConfigurationUnknownSettingException $e )
        {
            $this->assertEquals( "The setting 'TheOnlyGroup', 'NonExistent' does not exist.", $e->getMessage() );
        }
    }

    public function testSettingHasSettings()
    {
        $settings = ['TheOnlyGroup' => ['Setting1' => true, 'Setting2' => true]];
        $comments = [];
        $configuration = new ezcConfiguration( $settings, $comments );

        $found = $configuration->hasSettings( 'TheOnlyGroup', ['Setting1', 'Setting2'] );
        $this->assertEquals( true, $found );
    }

    public function testSettingHasSettings2()
    {
        $settings = ['TheOnlyGroup' => ['Setting1' => true]];
        $comments = [];
        $configuration = new ezcConfiguration( $settings, $comments );

        $found = $configuration->hasSettings( 'TheOnlyGroup', ['Setting1', 'Setting2'] );
        $this->assertEquals( false, $found );
    }

    public function testSettingHasSettings3()
    {
        $settings = [];
        $comments = [];
        $configuration = new ezcConfiguration( $settings, $comments );

        $found = $configuration->hasSettings( 'TheOnlyGroup', ['Setting1', 'Setting2'] );
        $this->assertEquals( false, $found );
    }

    public function testSettingHasGroup1()
    {
        $settings = ['TheOnlyGroup' => ['Setting1' => true]];
        $comments = [];
        $configuration = new ezcConfiguration( $settings, $comments );

        $found = $configuration->hasGroup( 'TheOnlyGroup' );
        $this->assertEquals( true, $found );
    }

    public function testSettingHasGroup2()
    {
        $settings = ['TheOnlyGroup' => ['Setting1' => true]];
        $comments = [];
        $configuration = new ezcConfiguration( $settings, $comments );

        $found = $configuration->hasGroup( 'NonExistingGroup' );
        $this->assertEquals( false, $found );
    }

    public function testSettings1()
    {
        $settings = ['TheOnlyGroup' => ['Setting1' => true, 'Setting2' => true]];
        $comments = [];
        $configuration = new ezcConfiguration( $settings, $comments );

        $found = $configuration->getSettings( 'TheOnlyGroup', ['Setting1', 'Setting2'] );
        $this->assertEquals( $settings['TheOnlyGroup'], $found );
    }

    public function testSettings2()
    {
        $settings = ['TheOnlyGroup' => ['Setting1' => true]];
        $comments = [];
        $configuration = new ezcConfiguration( $settings, $comments );

        try
        {
            $found = $configuration->getSettings( 'TheOnlyGroup', ['Setting1', 'Setting2'] );
            $this->fail( "Expected exception not thrown" );
        }
        catch ( ezcConfigurationUnknownSettingException $e )
        {
            $this->assertEquals( "The setting 'TheOnlyGroup', 'Setting2' does not exist.", $e->getMessage() );
        }
    }

    public function testSettings3()
    {
        $settings = [];
        $comments = [];
        $configuration = new ezcConfiguration( $settings, $comments );

        try
        {
            $found = $configuration->getSettings( 'TheOnlyGroup', ['Setting1', 'Setting2'] );
            $this->fail( "Expected exception not thrown" );
        }
        catch ( ezcConfigurationUnknownGroupException $e )
        {
            $this->assertEquals( "The settings group 'TheOnlyGroup' does not exist.", $e->getMessage() );
        }
    }

    public function testComments1()
    {
        $settings = ['TheOnlyGroup' => ['Setting1' => true, 'Setting2' => true]];
        $comments = ['TheOnlyGroup' => ['Setting1' => 'Houston, we have a comment!']];
        $expected = ['Setting1' => 'Houston, we have a comment!', 'Setting2' => false];
        $configuration = new ezcConfiguration( $settings, $comments );

        $found = $configuration->getComments( 'TheOnlyGroup', ['Setting1', 'Setting2'] );
        $this->assertEquals( $expected, $found );
    }

    public function testComments2()
    {
        $settings = ['TheOnlyGroup' => ['Setting1' => true]];
        $comments = [];
        $configuration = new ezcConfiguration( $settings, $comments );

        try
        {
            $found = $configuration->getComments( 'TheOnlyGroup', ['Setting1', 'Setting2'] );
            $this->fail( "Expected exception not thrown" );
        }
        catch ( ezcConfigurationUnknownSettingException $e )
        {
            $this->assertEquals( "The setting 'TheOnlyGroup', 'Setting2' does not exist.", $e->getMessage() );
        }
    }

    public function testComments3()
    {
        $settings = [];
        $comments = [];
        $configuration = new ezcConfiguration( $settings, $comments );

        try
        {
            $found = $configuration->getComments( 'TheOnlyGroup', ['Setting1', 'Setting2'] );
            $this->fail( "Expected exception not thrown" );
        }
        catch ( ezcConfigurationUnknownGroupException $e )
        {
            $this->assertEquals( "The settings group 'TheOnlyGroup' does not exist.", $e->getMessage() );
        }
    }

    public function testSetSettingsOverwrite()
    {
        $settings = ['TheOnlyGroup' => ['Existing1' => true, 'Existing2' => 'true', 'Existing3' => 1]];
        $comments = [];
        $configuration = new ezcConfiguration( $settings, $comments );
        $this->assertSame( false, $this->readAttribute( $configuration, 'isModified' ) );

        $configuration->setSettings(
            'TheOnlyGroup',
            ['Existing1', 'Existing2', 'Existing3'],
            [false, 'false', 0],
            ['Comment', null]
        );
        $this->assertSame( true, $this->readAttribute( $configuration, 'isModified' ) );
        $this->assertEquals(
            ['Existing1' => false, 'Existing2' => 'false', 'Existing3' => 0],
            $configuration->getSettings( 'TheOnlyGroup', ['Existing1', 'Existing2', 'Existing3'] )
        );
        $this->assertEquals(
            ['Existing1' => 'Comment', 'Existing2' => false, 'Existing3' => false],
            $configuration->getComments( 'TheOnlyGroup', ['Existing1', 'Existing2', 'Existing3'] )
        );
    }

    public function testSetSettingsNew()
    {
        $settings = ['TheOnlyGroup' => []];
        $comments = [];
        $configuration = new ezcConfiguration( $settings, $comments );
        $this->assertSame( false, $this->readAttribute( $configuration, 'isModified' ) );

        $configuration->setSettings( 'TheOnlyGroup', ['Existing1', 'Existing2', 'Existing3'], [false, 'false', 0] );
        $this->assertSame( true, $this->readAttribute( $configuration, 'isModified' ) );
        $this->assertEquals(
            ['Existing1' => false, 'Existing2' => 'false', 'Existing3' => 0],
            $configuration->getSettings( 'TheOnlyGroup', ['Existing1', 'Existing2', 'Existing3'] )
        );
    }

    public function testSettingsInGroup1()
    {
        $settings = ['TheOnlyGroup' => ['Existing1' => true, 'Existing2' => 'true', 'Existing3' => 1]];
        $comments = [];
        $configuration = new ezcConfiguration( $settings, $comments );
        $this->assertEquals(
            ['Existing1' => true, 'Existing2' => 'true', 'Existing3' => 1],
            $configuration->getSettingsInGroup( 'TheOnlyGroup' )
        );
    }

    public function testSettingsInGroup2()
    {
        $settings = [];
        $comments = [];
        $configuration = new ezcConfiguration( $settings, $comments );
        try
        {
            $configuration->getSettingsInGroup( 'TheOnlyGroup' );
            $this->fail( "Expected exception not thrown" );
        }
        catch ( ezcConfigurationUnknownGroupException $e )
        {
            $this->assertEquals( "The settings group 'TheOnlyGroup' does not exist.", $e->getMessage() );
        }
    }

    public function testGroupNames()
    {
        $settings = ['TheOnlyGroup' => ['Existing1' => true], 'TheSecondOnlyGroup' => ['Existing1' => true]];
        $comments = [];
        $configuration = new ezcConfiguration( $settings, $comments );
        $this->assertEquals( ['TheOnlyGroup', 'TheSecondOnlyGroup'], $configuration->getGroupNames() );
    }

    public function testRemoveSettings()
    {
        $settings = ['TheOnlyGroup' => ['Existing1' => true, 'Existing2' => 'true', 'Existing3' => 1]];
        $comments = ['TheOnlyGroup' => ['Existing1' => 'Comment1', 'Existing2' => 'Comment2']];
        $configuration = new ezcConfiguration( $settings, $comments );
        $this->assertSame( false, $this->readAttribute( $configuration, 'isModified' ) );

        $configuration->removeSettings( 'TheOnlyGroup', ['Existing1'] );
        $this->assertSame( true, $this->readAttribute( $configuration, 'isModified' ) );
        $this->assertEquals(
            ['Existing2' => 'true', 'Existing3' => 1],
            $configuration->getSettingsInGroup( 'TheOnlyGroup' )
        );
        $this->assertEquals( false, $configuration->hasSetting( 'TheOnlyGroup', 'Existing1' ) );

        try
        {
            $configuration->removeSettings( 'NotExistingGroup', ['Existing1'] );
        }
        catch ( ezcConfigurationUnknownGroupException $e )
        {
            $this->assertSame( "The settings group 'NotExistingGroup' does not exist.", $e->getMessage() );
        }

        try
        {
            $configuration->removeSettings( 'TheOnlyGroup', ['NonExisting'] );
        }
        catch ( ezcConfigurationUnknownSettingException $e )
        {
            $this->assertSame( "The setting 'TheOnlyGroup', 'NonExisting' does not exist.", $e->getMessage() );
        }
    }

    public function testAddGroup1()
    {
        $settings = [];
        $comments = [];
        $configuration = new ezcConfiguration( $settings, $comments );
        $this->assertSame( false, $this->readAttribute( $configuration, 'isModified' ) );

        $found = $configuration->addGroup( 'TheOnlyGroup', 'A comment' );
        $this->assertSame( true, $this->readAttribute( $configuration, 'isModified' ) );
        $this->assertSame( ['TheOnlyGroup' => ['#' => 'A comment']], $this->readAttribute( $configuration, 'comments' ) );
    }

    public function testAddGroup2()
    {
        $settings = ['TheOnlyGroup' => []];
        $comments = [];
        $configuration = new ezcConfiguration( $settings, $comments );

        try
        {
            $found = $configuration->addGroup( 'TheOnlyGroup', 'A comment' );
            $this->fail( "Expected exception not thrown" );
        }
        catch ( ezcConfigurationGroupExistsAlreadyException $e )
        {
            $this->assertEquals( "The settings group 'TheOnlyGroup' exists already.", $e->getMessage() );
        }
    }

    public function testSettingsNames1()
    {
        $settings = ['TheOnlyGroup' => ['Existing1' => true, 'Existing2' => 'true', 'Existing3' => 1]];
        $comments = [];
        $configuration = new ezcConfiguration( $settings, $comments );
        $this->assertEquals(
            ['Existing1', 'Existing2', 'Existing3'],
            $configuration->getSettingNames( 'TheOnlyGroup' )
        );
    }

    public function testSettingsNames2()
    {
        $settings = [];
        $comments = [];
        $configuration = new ezcConfiguration( $settings, $comments );
        try
        {
            $configuration->getSettingNames( 'TheOnlyGroup' );
            $this->fail( "Expected exception not thrown" );
        }
        catch ( ezcConfigurationUnknownGroupException $e )
        {
            $this->assertEquals( "The settings group 'TheOnlyGroup' does not exist.", $e->getMessage() );
        }
    }

    public function testRemoveGroup1()
    {
        $settings = ['TheOnlyGroup' => ['Existing1' => true, 'Existing2' => 'true', 'Existing3' => 1]];
        $comments = [];
        $configuration = new ezcConfiguration( $settings, $comments );
        $configuration->removeGroup( 'TheOnlyGroup' );
        $this->assertEquals( false, $configuration->hasGroup( 'TheOnlyGroup' ) );
        $this->assertSame( true, $this->readAttribute( $configuration, 'isModified' ) );
    }

    public function testRemoveGroup2()
    {
        $settings = [];
        $comments = [];
        $configuration = new ezcConfiguration( $settings, $comments );
        try
        {
            $configuration->removeGroup( 'TheOnlyGroup' );
            $this->fail( "Expected exception not thrown" );
        }
        catch ( ezcConfigurationUnknownGroupException $e )
        {
            $this->assertEquals( "The settings group 'TheOnlyGroup' does not exist.", $e->getMessage() );
        }
    }

    public function testRemoveGroupWithComments()
    {
        $settings = ['TheOnlyGroup' => ['Existing3' => 1]];
        $comments = ['TheOnlyGroup' => ['CommentExisting3' => 1]];
        $configuration = new ezcConfiguration( $settings, $comments );
        $configuration->removeGroup( 'TheOnlyGroup' );
        $this->assertEquals( false, $configuration->hasGroup( 'TheOnlyGroup' ) );
        $this->assertSame( true, $this->readAttribute( $configuration, 'isModified' ) );
    }

    public function testRemoveAllSettings()
    {
        $settings = ['TheOnlyGroup' => ['Existing1' => true], 'TheSecondOnlyGroup' => ['Existing1' => true]];
        $comments = [];
        $configuration = new ezcConfiguration( $settings, $comments );
        $configuration->removeAllSettings();
        $this->assertEquals( false, $configuration->hasGroup( 'TheOnlyGroup' ) );
        $this->assertEquals( false, $configuration->hasGroup( 'TheSecondOnlyGroup' ) );
        $this->assertSame( true, $this->readAttribute( $configuration, 'isModified' ) );
    }

    public function testIsModified()
    {
        $settings = [];
        $comments = [];
        $configuration = new ezcConfiguration( $settings, $comments );
        $this->assertSame( false, $this->readAttribute( $configuration, 'isModified' ) );
        $this->assertEquals( false, $configuration->isModified() );

        $found = $configuration->addGroup( 'TheOnlyGroup', 'A comment' );
        $this->assertSame( true, $this->readAttribute( $configuration, 'isModified' ) );
        $this->assertEquals( true, $configuration->isModified() );
    }

    public static function suite()
    {
         return new PHPUnit_Framework_TestSuite( 'ezcConfigurationTest' );
    }

}

?>
