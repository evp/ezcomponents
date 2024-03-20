<?php
/**
 * ezcGraphAxisBoxedRendererTest 
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
class ezcGraphAxisSpaceTest extends ezcGraphTestCase
{
    protected $basePath;

    protected $tempDir;

    protected static $i = 0;

	public static function suite()
	{
		return new PHPUnit_Framework_TestSuite( self::class );
	}

    protected function setUp()
    {
        $this->tempDir = $this->createTempDir( self::class . sprintf( '_%03d_', ++self::$i ) ) . '/';
        $this->basePath = __DIR__ . '/data/';
    }

    protected function tearDown()
    {
        if ( !$this->hasFailed() )
        {
            $this->removeTempDir();
        }
    }

    public static function getAxisConfiguration()
    {
        $axisLabelRenderer = [new ezcGraphAxisCenteredLabelRenderer(), new ezcGraphAxisExactLabelRenderer(), new ezcGraphAxisBoxedLabelRenderer(), new ezcGraphAxisRotatedLabelRenderer()];

        $axisSpaces = [.1, .2];

        $outerSpaces = [.05, .1];

        $xAlignements = [ezcGraph::LEFT, ezcGraph::RIGHT];

        $yAlignements = [ezcGraph::BOTTOM, ezcGraph::TOP];

        $calls = [];
        foreach ( $axisLabelRenderer as $xRenderer )
        {
            foreach ( $axisLabelRenderer as $yRenderer )
            {
                if ( $xRenderer === $yRenderer )
                {
                    continue;
                }

                foreach ( $axisSpaces as $xSpace )
                {
                    foreach ( $axisSpaces as $ySpace )
                    {
                        if ( $xSpace === $ySpace )
                        {
                            continue;
                        }

                        foreach ( $outerSpaces as $outerSpace )
                        {
                            foreach ( $xAlignements as $xAlign )
                            {
                                foreach ( $yAlignements as $yAlign )
                                {
                                    $calls[] = [$xRenderer, $yRenderer, $xSpace, $ySpace, $outerSpace, $xAlign, $yAlign];
                                }
                            }
                        }
                    }
                }
            }
        }

        return $calls;
    }

    /**
     * @dataProvider getAxisConfiguration
     */
    public function testAxisSpaceConfiguration( ezcGraphAxisLabelRenderer $xRenderer, ezcGraphAxisLabelRenderer $yRenderer, $xSpace, $ySpace, $outerSpace, $xAlign, $yAlign )
    {
        $filename = $this->tempDir . __FUNCTION__ . '_' . self::$i . '.svg';

        $chart = new ezcGraphLineChart();
        $chart->palette = new ezcGraphPaletteBlack();

        $chart->xAxis->axisLabelRenderer = $xRenderer;
        $chart->xAxis->axisSpace         = $xSpace;
        $chart->xAxis->outerAxisSpace    = $outerSpace;
        $chart->xAxis->position          = $xAlign;

        $chart->yAxis->axisLabelRenderer = $yRenderer;
        $chart->yAxis->axisSpace         = $ySpace;
        $chart->yAxis->outerAxisSpace    = $outerSpace;
        $chart->yAxis->position          = $yAlign;

        $chart->data['sampleData'] = new ezcGraphArrayDataSet( ['sample 1' => 250, 'sample 2' => 250, 'sample 3' => 0, 'sample 4' => 0, 'sample 5' => 500, 'sample 6' => 500] );

        $chart->render( 560, 250, $filename );
        $this->compare(
            $filename,
            $this->basePath . 'compare/' . self::class . '_' . __FUNCTION__ . '_' . self::$i . '.svg'
        );
    }
}
?>
