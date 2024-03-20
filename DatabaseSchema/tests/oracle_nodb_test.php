<?php

class ezcDatabaseSchemaOracleNoDbTest extends ezcTestCase
{
    public function testGeneratedSequenceAndTriggerNameMax30Chars()
    {
        $fields = [];
        $fields["veryverylong_id"] = new ezcDbSchemaField( "integer", 10, true, null, true, false );

        $indexes = [];
        $indexes["primary"] = new ezcDbSchemaIndex( ["veryverylong_id" => new ezcDbSchemaIndexField()] );

        $tables = ["veryverylongtablename" => new ezcDbSchemaTable( $fields, $indexes )];
        
        $schema = new ezcDbSchema( $tables );

        $oracleWriter = new ezcDbSchemaOracleWriter();
        $ddl = $oracleWriter->convertToDDL( $schema );

        $this->assertEquals( 6, count( $ddl ), "Invalid DDL query stack size. Queries might be shifted and following assertions are invalid!" );
        for ( $i = 0; $i < count( $ddl ); $i++ )
        {
            $this->assertNotContains( 'veryverylongtablename_veryverylong_id_seq', $ddl[$i] );
            $this->assertNotContains( 'veryverylongtablename_veryverylong_id_trg', $ddl[$i] );
        }

        $this->assertContains( 'veryverylongtab_veryverylo_seq', $ddl[2] );
        $this->assertContains( 'veryverylongtab_veryverylo_seq', $ddl[3] );
        $this->assertContains( 'veryverylongtab_veryverylo_trg', $ddl[3] );
        $this->assertEquals( 30, strlen( 'veryverylongtab_veryverylo_seq' ) );
    }

    public function testGeneratedConstraintNameMax30Chars()
    {
        $fields = [];
        $fields["oneid"] = new ezcDbSchemaField( "integer", 10, true, null, true, false );

        $indexes = [];
        $indexes["primary"] = new ezcDbSchemaIndex( ["oneid" => new ezcDbSchemaIndexField()] );

        $tables = ["ultraultraveryverylongtablename" => new ezcDbSchemaTable( $fields, $indexes )];

        $schema = new ezcDbSchema( $tables );

        $oracleWriter = new ezcDbSchemaOracleWriter();
        $ddl = $oracleWriter->convertToDDL( $schema );

        $this->assertEquals( 6, count( $ddl ), "Invalid DDL query stack size. Queries might be shifted and following assertions are invalid!" );
        for ( $i = 0; $i < count( $ddl ); $i++ )
        {
            $this->assertNotContains( 'ultraultraultraveryverylongtablename_pkey', $ddl[$i] );
        }

        $this->assertContains( 'ultraultraveryverylongtab_pkey', $ddl[4] );
        $this->assertEquals( 30, strlen( 'ultraultraveryverylongtab_pkey' ) );
    }

    static public function suite()
    {
        return new PHPUnit_Framework_TestSuite( 'ezcDatabaseSchemaOracleNoDbTest' );
    }
}
?>
