<?php
/**
 * ezcImageConversionTransformationTest
 *
 * @package ImageConversion
 * @version 1.3.8
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */


require_once __DIR__ . "/test_case.php";

/**
 * Test suite for ImageTransformation class.
 *
 * @package ImageConversion
 * @version 1.3.8
 */
class ezcImageConversionTransformationTest extends ezcImageConversionTestCase
{
    protected $testFiltersSuccess = [];

    protected $testFiltersFailure = [];

    protected $converter;

	public static function suite()
	{
		return new PHPUnit_Framework_TestSuite( "ezcImageConversionTransformationTest" );
	}

    protected function setUp()
    {
        try
        {
            $this->testFiltersSuccess = [
                0 => [0 => new ezcImageFilter(
                    "scaleExact",
                    ["width"     => 50, "height"    => 50, "direction" => ezcImageGeometryFilters::SCALE_BOTH]
                    ), 1 => new ezcImageFilter(
                    "crop",
                    ["x"     => 10, "width" => 30, "y"     => 10, "height"=> 30]
                    ), 2 => new ezcImageFilter(
                    "colorspace",
                    ["space" => ezcImageColorspaceFilters::COLORSPACE_GREY]
                    )],
                1 => [0 => new ezcImageFilter(
                    "scale",
                    ["width"     => 50, "height"    => 1000, "direction" => ezcImageGeometryFilters::SCALE_DOWN]
                    ), 2 => new ezcImageFilter(
                    "colorspace",
                    ["space" => ezcImageColorspaceFilters::COLORSPACE_MONOCHROME]
                    )],
                2 => [0 => new ezcImageFilter(
                    "scaleHeight",
                    ["height"    => 70, "direction" => ezcImageGeometryFilters::SCALE_BOTH]
                    ), 2 => new ezcImageFilter(
                    "colorspace",
                    ["space" => ezcImageColorspaceFilters::COLORSPACE_SEPIA]
                    )],
                // Optional parameter dismissed
                3 => [0 => new ezcImageFilter(
                    "scale",
                    ["width"     => 50, "height"    => 50]
                    )],
            ];
            $this->testFiltersFailure = [
                // Nonexistant filter
                0 => [0 => new ezcImageFilter(
                    "toby",
                    ["width"     => 50, "height"    => 50, "direction" => ezcImageGeometryFilters::SCALE_BOTH]
                    ), 1 => new ezcImageFilter(
                    "crop",
                    ["x"     => 10, "width" => 30, "y"     => 10, "height"=> 30]
                    ), 2 => new ezcImageFilter(
                    "colorspace",
                    ["space" => ezcImageColorspaceFilters::COLORSPACE_GREY]
                    )],
                // Missing option
                1 => [0 => new ezcImageFilter(
                    "scale",
                    []
                    ), 2 => new ezcImageFilter(
                    "colorspace",
                    ["space" => ezcImageColorspaceFilters::COLORSPACE_MONOCHROME]
                    )],
            ];

            $conversionsIn = ["image/gif"  => "image/png", "image/xpm"  => "image/jpeg", "image/wbmp" => "image/jpeg"];
            if ( ezcBaseFeatures::os() === 'Windows' )
            {
                unset( $conversionsIn["image/xpm"] );
            }
  
            $settings = new ezcImageConverterSettings(
                [new ezcImageHandlerSettings( "GD", "ezcImageGdHandler" )],
                $conversionsIn
            );
            $this->converter = new ezcImageConverter( $settings );
        }
        catch ( Exception $e )
        {
            $this->markTestSkipped( $e->getMessage() );
        }
    }

    protected function tearDown()
    {
        unset( $this->converter );
    }

    public function testConstructSuccess()
    {
        $filtersIn = [0 => new ezcImageFilter(
            "scale",
            ["width"     => 50, "height"    => 50, "direction" => ezcImageGeometryFilters::SCALE_BOTH]
        ), 1 => new ezcImageFilter(
            "scaleWidth",
            ["width"     => 40, "direction" => ezcImageGeometryFilters::SCALE_BOTH]
        ), 2 => new ezcImageFilter(
            "crop",
            ["xStart"     => 10, "xEnd"       => 40, "yStart"     => 10, "yEnd"       => 40]
        )];

        $mimeIn = ["image/jpeg"];

        $trans = new ezcImageTransformation( $this->converter, "test", $filtersIn, $mimeIn );

        $this->assertAttributeEquals(
            $mimeIn,
            "mimeOut",
            $trans,
            "MIME types not registered correctly in transformation."
        );
        $this->assertAttributeEquals(
            $filtersIn,
            "filters",
            $trans,
            "Filters not registered correctly in transformation."
        );
    }

    public function testConstructFailureFilterNotAvailable()
    {
        $filtersIn = [0 => new ezcImageFilter(
            "toby",
            ["width"     => 50, "height"    => 50, "direction" => ezcImageGeometryFilters::SCALE_BOTH]
        )];

        $mimeIn = ["image/jpeg"];

        try
        {
            $trans = new ezcImageTransformation( $this->converter, "test", $filtersIn, $mimeIn );
        }
        catch ( ezcImageFilterNotAvailableException $e )
        {
            return;
        }
        $this->fail( "Transformation did not throw exception on invalid filter." );
    }

    public function testConstructFailureInvalidMimeType()
    {
        $filtersIn = [0 => new ezcImageFilter(
            "scale",
            ["width"     => 50, "height"    => 50, "direction" => ezcImageGeometryFilters::SCALE_BOTH]
        )];

        $mimeIn = ["application/toby"];

        try
        {
            $trans = new ezcImageTransformation( $this->converter, "test", $filtersIn, $mimeIn );
        }
        catch ( ezcImageMimeTypeUnsupportedException $e )
        {
            return;
        }
        $this->fail( "Transformation did not throw exception on invalid MIME type." );
    }

    public function testAddFilterSuccess()
    {
        $filtersIn = [0 => new ezcImageFilter(
            "scale",
            ["width"     => 50, "height"    => 50, "direction" => ezcImageGeometryFilters::SCALE_BOTH]
        )];

        $newFilter = new ezcImageFilter(
            "scaleWidth",
            ["width"     => 40, "direction" => ezcImageGeometryFilters::SCALE_BOTH]
        );

        $filtersOut = $filtersIn;
        $filtersOut[] = $newFilter;

        $mimeIn = ["image/jpeg"];

        $trans = new ezcImageTransformation( $this->converter, "test", $filtersIn, $mimeIn );

        $trans->addFilter( $newFilter );

        $this->assertAttributeEquals(
            $filtersOut,
            "filters",
            $trans,
            "Filters not added correctly to transformation."
        );
    }

    public function testAddFilterFailure()
    {
        $filtersIn = [0 => new ezcImageFilter(
            "scale",
            ["width"     => 50, "height"    => 50, "direction" => ezcImageGeometryFilters::SCALE_BOTH]
        )];

        $newFilter = new ezcImageFilter(
            "toby",
            ["width"     => 40, "direction" => ezcImageGeometryFilters::SCALE_BOTH]
        );

        $filtersOut = $filtersIn;
        $filtersOut[] = $newFilter;

        $mimeIn = ["image/jpeg"];

        $trans = new ezcImageTransformation( $this->converter, "test", $filtersIn, $mimeIn );

        try
        {
            $trans->addFilter( $newFilter );
        }
        catch ( ezcImageFilterNotAvailableException $e )
        {
            return;
        }
        $this->fail( "Transformation did not throw exception on invalid filter." );
    }

    public function testGetOutMimeSuccessNoTransform()
    {
        $filtersIn = [0 => new ezcImageFilter(
            "scale",
            ["width"     => 50, "height"    => 50, "direction" => ezcImageGeometryFilters::SCALE_BOTH]
        )];

        $mimeIn = ["image/jpeg"];

        $trans = new ezcImageTransformation( $this->converter, "test", $filtersIn, $mimeIn );

        $this->assertEquals(
            "image/jpeg",
            $trans->getOutMime( $this->testFiles["jpeg"] ),
            "Transformation returned incorrect output MIME type."
        );
    }

    public function testGetOutMimeSuccessExplicitTransform()
    {
        $filtersIn = [0 => new ezcImageFilter(
            "scale",
            ["width"     => 50, "height"    => 50, "direction" => ezcImageGeometryFilters::SCALE_BOTH]
        )];

        $mimeIn = ["image/jpeg", "image/png"];

        $trans = new ezcImageTransformation( $this->converter, "test", $filtersIn, $mimeIn );

        $this->assertEquals(
            "image/png",
            $trans->getOutMime( $this->testFiles["gif_nonanimated"] ),
            "Transformation returned incorrect output MIME type."
        );
    }

    public function testGetOutMimeSuccessImplicitTransform()
    {
        $filtersIn = [0 => new ezcImageFilter(
            "scale",
            ["width"     => 50, "height"    => 50, "direction" => ezcImageGeometryFilters::SCALE_BOTH]
        )];

        $mimeIn = ["image/jpeg"];

        $trans = new ezcImageTransformation( $this->converter, "test", $filtersIn, $mimeIn );

        $this->assertEquals(
            "image/jpeg",
            $trans->getOutMime( $this->testFiles["gif_nonanimated"] ),
            "Transformation returned incorrect output MIME type."
        );
    }

    public function testTransformSuccessPng_1()
    {
        $trans = new ezcImageTransformation(
            $this->converter,
            "test",
            $this->testFiltersSuccess[0],
            ["image/jpeg", "image/png"]
        );
        $trans->transform( $this->testFiles["png"], $this->getTempPath() );
        $this->assertImageSimilar(
            $this->getReferencePath(),
            $this->getTempPath(),
            "Image not generated successfully.",
            // ezcImageConversionTestCase::DEFAULT_SIMILARITY_GAP
            20
        );
    }

    public function testTransformFailureText()
    {
        $trans = new ezcImageTransformation(
            $this->converter,
            "test",
            $this->testFiltersSuccess[0],
            ["image/jpeg", "image/png"]
        );

        try
        {
            $trans->transform( $this->testFiles["text"], $this->getTempPath() );
        }
        catch ( ezcImageTransformationException $e )
        {
            return;
        }
        $this->fail( "Exception not thrown on invalid image input." );
    }

    public function testTransformSuccessPng_2()
    {
        $trans = new ezcImageTransformation(
            $this->converter,
            "test",
            $this->testFiltersSuccess[1],
            ["image/jpeg", "image/png"]
        );
        $trans->transform( $this->testFiles["png"], $this->getTempPath() );
        $this->assertImageSimilar(
            $this->getReferencePath(),
            $this->getTempPath(),
            "Image not generated successfully.",
            ezcImageConversionTestCase::DEFAULT_SIMILARITY_GAP
        );
    }

    public function testTransformSuccessPng_3()
    {
        $trans = new ezcImageTransformation(
            $this->converter,
            "test",
            $this->testFiltersSuccess[2],
            ["image/jpeg", "image/png"]
        );
        $trans->transform( $this->testFiles["png"], $this->getTempPath() );
        $this->assertImageSimilar(
            $this->getReferencePath(),
            $this->getTempPath(),
            "Image not generated successfully.",
            // ezcImageConversionTestCase::DEFAULT_SIMILARITY_GAP
            40
        );
    }

    public function testTransformSuccessPng_4()
    {
        $trans = new ezcImageTransformation(
            $this->converter,
            "test",
            $this->testFiltersSuccess[3],
            ["image/jpeg", "image/png"]
        );
        $trans->transform( $this->testFiles["png"], $this->getTempPath() );
        $this->assertImageSimilar(
            $this->getReferencePath(),
            $this->getTempPath(),
            "Image not generated successfully.",
            // ezcImageConversionTestCase::DEFAULT_SIMILARITY_GAP
            20
        );
    }

    public function testTransformSuccessJpeg_1()
    {
        $trans = new ezcImageTransformation(
            $this->converter,
            "test",
            $this->testFiltersSuccess[0],
            ["image/jpeg", "image/png"]
        );
        $trans->transform( $this->testFiles["jpeg"], $this->getTempPath() );
        $this->assertImageSimilar(
            $this->getReferencePath(),
            $this->getTempPath(),
            "Image not generated successfully.",
            ezcImageConversionTestCase::DEFAULT_SIMILARITY_GAP
        );
    }

    public function testTransformSuccessJpeg_2()
    {
        $trans = new ezcImageTransformation(
            $this->converter,
            "test",
            $this->testFiltersSuccess[1],
            ["image/jpeg", "image/png"]
        );
        $trans->transform( $this->testFiles["jpeg"], $this->getTempPath() );
        $this->assertImageSimilar(
            $this->getReferencePath(),
            $this->getTempPath(),
            "Image not generated successfully.",
            ezcImageConversionTestCase::DEFAULT_SIMILARITY_GAP
        );
    }

    public function testTransformSuccessJpeg_3()
    {
        $trans = new ezcImageTransformation(
            $this->converter,
            "test",
            $this->testFiltersSuccess[2],
            ["image/jpeg", "image/png"]
        );
        $trans->transform( $this->testFiles["jpeg"], $this->getTempPath() );
        $this->assertImageSimilar(
            $this->getReferencePath(),
            $this->getTempPath(),
            "Image not generated successfully.",
            ezcImageConversionTestCase::DEFAULT_SIMILARITY_GAP
        );
    }

    public function testTransformSuccessGif_1()
    {
        $trans = new ezcImageTransformation(
            $this->converter,
            "test",
            $this->testFiltersSuccess[0],
            ["image/jpeg", "image/png"]
        );
        $trans->transform( $this->testFiles["gif_nonanimated"], $this->getTempPath() );
        $this->assertImageSimilar(
            $this->getReferencePath(),
            $this->getTempPath(),
            "Image not generated successfully.",
            ezcImageConversionTestCase::DEFAULT_SIMILARITY_GAP
        );
    }

    public function testTransformSuccessGif_2()
    {
        $trans = new ezcImageTransformation(
            $this->converter,
            "test",
            $this->testFiltersSuccess[1],
            ["image/jpeg", "image/png"]
        );
        $trans->transform( $this->testFiles["gif_nonanimated"], $this->getTempPath() );
        $this->assertImageSimilar(
            $this->getReferencePath(),
            $this->getTempPath(),
            "Image not generated successfully.",
            ezcImageConversionTestCase::DEFAULT_SIMILARITY_GAP
        );
    }

    public function testTransformSuccessGif_3()
    {
        $trans = new ezcImageTransformation(
            $this->converter,
            "test",
            $this->testFiltersSuccess[2],
            ["image/jpeg", "image/png"]
        );
        $trans->transform( $this->testFiles["gif_nonanimated"], $this->getTempPath() );
        $this->assertImageSimilar(
            $this->getReferencePath(),
            $this->getTempPath(),
            "Image not generated successfully.",
            ezcImageConversionTestCase::DEFAULT_SIMILARITY_GAP
        );
    }

    public function testTransformSuccessGifAnimated()
    {
        $trans = new ezcImageTransformation(
            $this->converter,
            "test",
            $this->testFiltersSuccess[2],
            ["image/jpeg", "image/png"]
        );
        $trans->transform( $this->testFiles["gif_animated"], $this->getTempPath() );
        $this->assertImageSimilar(
            $this->getReferencePath(),
            $this->getTempPath(),
            "Image not generated successfully.",
            7000
        );
    }

    public function testTransformFailureFilterNotAvailable()
    {
        try
        {
            $trans = new ezcImageTransformation(
                $this->converter,
                "test",
                $this->testFiltersFailure[0],
                ["image/jpeg", "image/png"]
            );
            $trans->transform( $this->testFiles["jpeg"], $this->getTempPath() );
        }
        catch ( ezcImageFilterNotAvailableException $e )
        {
            return;
        }
        $this->fail( "Expected exception not thrown." );

    }

    public function testTransformFailureMissingFilterOption()
    {
        $trans = new ezcImageTransformation(
            $this->converter,
            "test",
            $this->testFiltersFailure[1],
            ["image/jpeg", "image/png"]
        );
        try
        {
            $trans->transform( $this->testFiles["jpeg"], $this->getTempPath() );
        }
        catch ( ezcImageTransformationException $e )
        {
            return;
        }
        $this->fail( "Expected exception not thrown." );

    }

    public function testTransformFailureFileNotFound()
    {
        $trans = new ezcImageTransformation(
            $this->converter,
            "test",
            $this->testFiltersFailure[1],
            ["image/jpeg", "image/png"]
        );
        try
        {
            $trans->transform( $this->testFiles["nonexistent"], $this->getTempPath() );
        }
        catch ( ezcBaseFileNotFoundException $e )
        {
            return;
        }
        $this->fail( "Expected exception not thrown." );

    }

    // Test for bug #8137: ImageConversion - ezcImageTransformation fails on
    public function testMultiTransform()
    {
        $mimeOut = ["image/jpeg"];
        $trans = new ezcImageTransformation( $this->converter, "test", $this->testFiltersSuccess[0], $mimeOut );

        $trans->transform( $this->testFiles["jpeg"], $this->getTempPath( "jpeg" ) );
        $trans->transform( $this->testFiles["png"], $this->getTempPath( "png" ) );

        $this->assertImageSimilar(
            $this->getReferencePath( "jpeg" ),
            $this->getTempPath( "jpeg" ),
            "Transformation did not produce correct output.",
            2000
        );
        $this->assertImageSimilar(
            $this->getReferencePath( "png" ),
            $this->getTempPath( "png" ),
            "Transformation did not produce correct output.",
            2000
        );
    }
    
    // Test for bug #10949: rename php error if file allread exists
    public function testDoubleTransform()
    {
        $mimeOut = ["image/jpeg"];
        $trans = new ezcImageTransformation( $this->converter, "test", $this->testFiltersSuccess[0], $mimeOut );

        $resFile = $this->getTempPath( "jpeg" );
        $trans->transform( $this->testFiles["jpeg"], $resFile );
        $trans->transform( $this->testFiles["jpeg"], $resFile );

        // Should not fail or produce a notice
    }

    public function testTransformQualityLow()
    {
        $mimeOut = ["image/jpeg"];
        $opts = new ezcImageSaveOptions();
        $opts->quality     = 0;
        // irrelevant, but set!
        $opts->compression = 9;
        $dstPath =  $this->getTempPath( "jpeg" );
        
        $trans = new ezcImageTransformation( $this->converter, "test", [], $mimeOut, $opts );
        $trans->transform( $this->testFiles["png"], $dstPath );

        $this->assertThat(
            filesize( $dstPath ),
            $this->lessThan( 2000 ),
            "File saved with too high quality."
        );
    }

    public function testTransformQualityHigh()
    {
        $mimeOut = ["image/jpeg"];
        $opts = new ezcImageSaveOptions();
        $opts->quality     = 100;
        // irrelevant, but set!
        $opts->compression = 9;
        $dstPath =  $this->getTempPath( "jpeg" );
        
        $trans = new ezcImageTransformation( $this->converter, "test", [], $mimeOut, $opts );
        $trans->transform( $this->testFiles["png"], $dstPath );

        $this->assertThat(
            filesize( $dstPath ),
            $this->greaterThan( 30000 ),
            "File saved with too low quality."
        );
    }

    public function testTransformCompressionLow()
    {
        $mimeOut = ["image/png"];
        $opts = new ezcImageSaveOptions();
        $opts->compression = 0;
        // irrelevant, but set!
        $opts->quality     = 100;
        $dstPath =  $this->getTempPath( "png" );
        
        $trans = new ezcImageTransformation( $this->converter, "test", [], $mimeOut, $opts );
        $trans->transform( $this->testFiles["png"], $dstPath );

        $this->assertThat(
            filesize( $dstPath ),
            $this->greaterThan( 100000 ),
            "File saved with too high compression."
        );
    }

    public function testTransformCompressionHigh()
    {
        $mimeOut = ["image/png"];
        $opts = new ezcImageSaveOptions();
        $opts->compression = 9;
        // irrelevant, but set!
        $opts->quality     = 100;
        $dstPath =  $this->getTempPath( "png" );
        
        $trans = new ezcImageTransformation( $this->converter, "test", [], $mimeOut, $opts );
        $trans->transform( $this->testFiles["png"], $dstPath );

        $this->assertThat(
            filesize( $dstPath ),
            $this->lessThan( 40000 ),
            "File saved with too low compression."
        );
    }

    public function testApplyTransformationFailureFileNotReadable()
    {
        $tmpDir  = $this->createTempDir( self::class );
        $srcFile = "$tmpDir/non_readable_png.png";

        copy( $this->testFiles['png'], $srcFile );
        chmod( $srcFile, 0000 );

        $trans = new ezcImageTransformation( $this->converter, "test", [], ['image/jpeg'] );
        try
        {
            $trans->transform( $srcFile, $srcFile );
            $this->fail( 'Exception not throwen with unreadable file.' );
        }
        catch ( ezcBaseFilePermissionException $e )
        {}

        $this->removeTempDir();
    }

    public function testApplyTransformationFailureDestinationNotOverwriteable()
    {
        $tmpDir  = $this->createTempDir( self::class );
        $dstFile = "$tmpDir/non_writeable_png.png";

        touch( $dstFile );
        chmod( $dstFile, 0444 );
        chmod( dirname( $dstFile ), 0555 );
        clearstatcache();

        $trans = new ezcImageTransformation( $this->converter, "test", [], ['image/jpeg'] );
        $exceptionThrown = false;
        try
        {
            $trans->transform( $this->testFiles['png'], $dstFile );
        }
        catch ( ezcImageFileNotProcessableException $e )
        {
            $exceptionThrown = true;
        }
        
        chmod( $dstFile, 0666 );
        chmod( dirname( $dstFile ), 0777 );
        clearstatcache();

        $this->removeTempDir();
        if ( !$exceptionThrown )
        {
            $this->fail( 'Exception not throwen with not writeable file.' );
        }
    }

    public function testCreateTransformationFailureInvalidFilters()
    {
        $filters   = $this->testFiltersSuccess[0];
        $filters[] = new stdClass();

        try
        {
            $trans = new ezcImageTransformation( $this->converter, 'test', $filters, ['image/jpeg'] );
            $this->fail( 'Exception not throwen on invalid filter in initial filter array.' );
        }
        catch ( ezcBaseSettingValueException $e )
        {}
    }

    public function testAddFilterBefore()
    {
        $newFilter = new ezcImageFilter(
            'scale',
            ['width' => 10, 'height' => 10]
        );
        $filtersBefore = $this->testFiltersSuccess[0];
        $filtersAfter  = $filtersBefore;
        array_splice( $filtersAfter, 1, 0, [$newFilter] );

        $trans = new ezcImageTransformation( $this->converter, 'test', $filtersBefore, ['image/jpeg'] );

        $trans->addFilter( $newFilter, 1 );            

        $this->assertAttributeEquals(
            $filtersAfter,
            'filters',
            $trans
        );
    }

    public function testTransformationChangingHandlersForFilters()
    {
        $gdSettings = new ezcImageHandlerSettings( 'GD', 'ezcImageGdHandler' );
        $imSettings = new ezcImageHandlerSettings( 'IM', 'ezcImageImagemagickHandler');
        try
        {
            $gd = new ezcImageGdHandler( $gdSettings );
            $im = new ezcImageImagemagickHandler( $imSettings );
        }
        catch ( ezcImageHandlerNotAvailableException $e )
        {
            $this->markTestSkipped( 'Needs both image handlers.' );
        }

        $conv = new ezcImageConverter(
            new ezcImageConverterSettings(
                [$gdSettings, $imSettings]
            )
        );

        $trans = new ezcImageTransformation(
            $conv,
            'test',
            [new ezcImageFilter(
                'scale',
                ['width' => 100, 'height' => 100]
            ), new ezcImageFilter(
                'swirl',
                ['value' => 100]
            )],
            ['image/png']
        );


        $trans->transform( $this->testFiles['png'], $this->getTempPath() );

        $this->assertImageSimilar(
            $this->getReferencePath(),
            $this->getTempPath(),
            "Image  not generated successfully",
            500
        );
    }

    public function testTransformationChangingHandlersForConversion()
    {
        $gdSettings = new ezcImageHandlerSettings( 'GD', 'ezcImageGdHandler' );
        $imSettings = new ezcImageHandlerSettings( 'IM', 'ezcImageImagemagickHandler');
        try
        {
            $gd = new ezcImageGdHandler( $gdSettings );
            $im = new ezcImageImagemagickHandler( $imSettings );
        }
        catch ( ezcImageHandlerNotAvailableException $e )
        {
            $this->markTestSkipped( 'Needs both image handlers.' );
        }

        $conv = new ezcImageConverter(
            new ezcImageConverterSettings(
                [$gdSettings, $imSettings]
            )
        );

        $trans = new ezcImageTransformation(
            $conv,
            'test',
            [new ezcImageFilter(
                'scale',
                ['width' => 100, 'height' => 100]
            )],
            ['image/g3fax']
        );


        $trans->transform( $this->testFiles['png'], $this->getTempPath() );

        // No assertion, must simply not throw an exception and just raises code coverage
    }

}
?>
