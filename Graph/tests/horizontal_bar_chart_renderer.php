<?php
/**
 * ezcGraphRenderer2dTest 
 * 
 * @package Graph
 * @version 1.5
 * @subpackage Tests
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

require_once __DIR__ . '/test_case.php';

/**
 * Tests for ezcGraph class.
 * 
 * @package Graph
 * @subpackage Tests
 */
class ezcGraphHorizontalBarRendererTests extends ezcGraphTestCase
{
    protected $basePath;

    protected $tempDir;

	public static function suite()
	{
	    return new PHPUnit_Framework_TestSuite( self::class );
	}

    protected function setUp()
    {
        static $i = 0;
        $this->tempDir = $this->createTempDir( self::class . sprintf( '_%03d_', ++$i ) ) . '/';
        $this->basePath = __DIR__ . '/data/';
    }

    protected function tearDown()
    {
        if ( !$this->hasFailed() )
        {
            $this->removeTempDir();
        }
    }

    public function testRenderBasicHorizontalBarChart()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.svg';

        $chart = new ezcGraphHorizontalBarChart();

        $chart->data['Set 1'] = new ezcGraphArrayDataSet( ['sample 1' => 234, 'sample 2' => 151, 'sample 3' => 324, 'sample 4' => 120, 'sample 5' => 1] );

        $chart->render( 500, 200, $filename );

        $this->compare(
            $filename,
            $this->basePath . 'compare/' . self::class . '_' . __FUNCTION__ . '.svg'
        );
    }

    public function testRenderHorizontalBarChartMultipleBars()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.svg';

        $chart = new ezcGraphHorizontalBarChart();

        $chart->data['Set 1'] = new ezcGraphArrayDataSet( ['sample 1' => 234, 'sample 2' => 151, 'sample 3' => 324, 'sample 4' => 120, 'sample 5' => 1] );
        $chart->data['Set 2'] = new ezcGraphArrayDataSet( ['sample 1' => 543, 'sample 2' => 234, 'sample 3' => 298, 'sample 4' => 5, 'sample 5' => 124] );

        $chart->render( 500, 200, $filename );

        $this->compare(
            $filename,
            $this->basePath . 'compare/' . self::class . '_' . __FUNCTION__ . '.svg'
        );
    }

    public function testRenderHorizontalBarChartNegativeValues()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.svg';

        $chart = new ezcGraphHorizontalBarChart();

        $chart->data['Set 1'] = new ezcGraphArrayDataSet( ['sample 1' => 234, 'sample 2' => -151, 'sample 3' => -324, 'sample 4' => 120, 'sample 5' =>  1] );
        $chart->data['Set 2'] = new ezcGraphArrayDataSet( ['sample 1' => 543, 'sample 2' =>  234, 'sample 3' => -298, 'sample 4' => 5, 'sample 5' => -124] );

        $chart->render( 500, 200, $filename );

        $this->compare(
            $filename,
            $this->basePath . 'compare/' . self::class . '_' . __FUNCTION__ . '.svg'
        );
    }
}
?>
