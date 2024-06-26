<?php
/**
 * File containing the ezcGraphSvgDriverOption class
 *
 * @package Graph
 * @version 1.5
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */
/**
 * Class containing the extended options for the SVG driver.
 *
 * <code>
 *   $graph = new ezcGraphPieChart();
 *   $graph->background->color = '#FFFFFFFF';
 *   $graph->title = 'Access statistics';
 *   $graph->legend = false;
 *   
 *   $graph->data['Access statistics'] = new ezcGraphArrayDataSet( array(
 *       'Mozilla' => 19113,
 *       'Explorer' => 10917,
 *       'Opera' => 1464,
 *       'Safari' => 652,
 *       'Konqueror' => 474,
 *   ) );
 *   
 *   $graph->driver->options->templateDocument = dirname( __FILE__ ) . '/template.svg';
 *   $graph->driver->options->graphOffset = new ezcGraphCoordinate( 25, 40 );
 *   $graph->driver->options->insertIntoGroup = 'ezcGraph';
 *   
 *   $graph->render( 400, 200, 'tutorial_driver_svg.svg' );
 * </code>
 *
 * @property string $encoding
 *           Encoding of the SVG XML document
 * @property float $assumedNumericCharacterWidth
 *           Assumed percentual average width of chars in numeric strings with 
 *           the used font.
 * @property float $assumedTextCharacterWidth
 *           Assumed percentual average width of chars in non numeric strings
 *           with the used font.
 * @property string $strokeLineCap
 *           This specifies the shape to be used at the end of open subpaths 
 *           when they are stroked.
 * @property string $strokeLineJoin
 *           This specifies the shape to be used at the edges of paths.
 * @property string $shapeRendering
 *           "The creator of SVG content might want to provide a hint to the
 *           implementation about what tradeoffs to make as it renders vector
 *           graphics elements such as 'path' elements and basic shapes such as
 *           circles and rectangles."
 * @property string $colorRendering
 *           "The creator of SVG content might want to provide a hint to the
 *           implementation about how to make speed vs. quality tradeoffs as it
 *           performs color interpolation and compositing. The 
 *           'color-rendering' property provides a hint to the SVG user agent 
 *           about how to optimize its color interpolation and compositing 
 *           operations."
 * @property string $textRendering
 *           "The creator of SVG content might want to provide a hint to the
 *           implementation about what tradeoffs to make as it renders text."
 * @property mixed $templateDocument
 *           Use existing SVG document as template to insert graph into. If
 *           insertIntoGroup is not set, a new group will be inserted in the 
 *           svg root node.
 * @property mixed $insertIntoGroup
 *           ID of a SVG group node to insert the graph. Only works with a 
 *           custom template document.
 * @property ezcGraphCoordinate $graphOffset
 *           Offset of the graph in the svg.
 * @property string $idPrefix
 *           Prefix used for the ids in SVG documents.
 * @property string $linkCursor
 *           CSS value for cursor property used for linked SVG elements
 * 
 * @version 1.5
 * @package Graph
 */
class ezcGraphSvgDriverOptions extends ezcGraphDriverOptions
{
    /**
     * Constructor
     * 
     * @param array $options Default option array
     * @return void
     * @ignore
     */
    public function __construct( array $options = [] )
    {
        $this->properties['encoding'] = null;
        $this->properties['assumedNumericCharacterWidth'] = .62;
        $this->properties['assumedTextCharacterWidth'] = .53;
        $this->properties['strokeLineJoin'] = 'round';
        $this->properties['strokeLineCap'] = 'round';
        $this->properties['shapeRendering'] = 'geometricPrecision';
        $this->properties['colorRendering'] = 'optimizeQuality';
        $this->properties['textRendering'] = 'optimizeLegibility';
        $this->properties['templateDocument'] = false;
        $this->properties['insertIntoGroup'] = false;
        $this->properties['graphOffset'] = new ezcGraphCoordinate( 0, 0 );
        $this->properties['idPrefix'] = 'ezcGraph';
        $this->properties['linkCursor'] = 'pointer';

        parent::__construct( $options );
    }

    /**
     * Set an option value
     * 
     * @param string $propertyName 
     * @param mixed $propertyValue 
     * @throws ezcBasePropertyNotFoundException
     *          If a property is not defined in this class
     * @return void
     * @ignore
     */
    public function __set( $propertyName, $propertyValue )
    {
        switch ( $propertyName )
        {
            case 'assumedNumericCharacterWidth':
                if ( !is_numeric( $propertyValue ) ||
                     ( $propertyValue < 0 ) )
                {
                    throw new ezcBaseValueException( $propertyName, $propertyValue, 'float > 0' );
                }

                $this->properties['assumedNumericCharacterWidth'] = (float) $propertyValue;
                break;
            case 'assumedTextCharacterWidth':
                if ( !is_numeric( $propertyValue ) ||
                     ( $propertyValue < 0 ) )
                {
                    throw new ezcBaseValueException( $propertyName, $propertyValue, 'float > 0' );
                }

                $this->properties['assumedTextCharacterWidth'] = (float) $propertyValue;
                break;
            case 'strokeLineJoin':
                $values = ['round', 'miter', 'bevel', 'inherit'];

                if ( in_array( $propertyValue, $values, true ) )
                {
                    $this->properties['strokeLineJoin'] = $propertyValue;
                }
                else
                {
                    throw new ezcBaseValueException( $propertyName, $propertyValue, implode( $values, ', ' ) );
                }
                break;
            case 'strokeLineCap':
                $values = ['round', 'butt', 'square', 'inherit'];

                if ( in_array( $propertyValue, $values, true ) )
                {
                    $this->properties['strokeLineCap'] = $propertyValue;
                }
                else
                {
                    throw new ezcBaseValueException( $propertyName, $propertyValue, implode( $values, ', ' ) );
                }
                break;
            case 'shapeRendering':
                $values = ['auto', 'optimizeSpeed', 'crispEdges', 'geometricPrecision', 'inherit'];

                if ( in_array( $propertyValue, $values, true ) )
                {
                    $this->properties['shapeRendering'] = $propertyValue;
                }
                else
                {
                    throw new ezcBaseValueException( $propertyName, $propertyValue, implode( $values, ', ' ) );
                }
                break;
            case 'colorRendering':
                $values = ['auto', 'optimizeSpeed', 'optimizeQuality', 'inherit'];

                if ( in_array( $propertyValue, $values, true ) )
                {
                    $this->properties['colorRendering'] = $propertyValue;
                }
                else
                {
                    throw new ezcBaseValueException( $propertyName, $propertyValue, implode( $values, ', ' ) );
                }
                break;
            case 'textRendering':
                $values = ['auto', 'optimizeSpeed', 'optimizeLegibility', 'geometricPrecision', 'inherit'];

                if ( in_array( $propertyValue, $values, true ) )
                {
                    $this->properties['textRendering'] = $propertyValue;
                }
                else
                {
                    throw new ezcBaseValueException( $propertyName, $propertyValue, implode( $values, ', ' ) );
                }
                break;
            case 'templateDocument':
                if ( !is_file( $propertyValue ) || !is_readable( $propertyValue ) )
                {
                    throw new ezcBaseFileNotFoundException( $propertyValue );
                }
                else
                {
                    $this->properties['templateDocument'] = realpath( $propertyValue );
                }
                break;
            case 'insertIntoGroup':
                if ( !is_string( $propertyValue ) )
                {
                    throw new ezcBaseValueException( $propertyName, $propertyValue, 'string' );
                }
                else
                {
                    $this->properties['insertIntoGroup'] = $propertyValue;
                }
                break;
            case 'graphOffset':
                if ( $propertyValue instanceof ezcGraphCoordinate )
                {
                    $this->properties['graphOffset'] = $propertyValue;
                }
                else
                {
                    throw new ezcBaseValueException( $propertyName, $propertyValue, 'ezcGraphCoordinate' );
                }
                break;
            case 'idPrefix':
                $this->properties['idPrefix'] = (string) $propertyValue;
                break;
            case 'encoding':
                $this->properties['encoding'] = (string) $propertyValue;
                break;
            case 'linkCursor':
                $this->properties['linkCursor'] = (string) $propertyValue;
                break;
            default:
                parent::__set( $propertyName, $propertyValue );
                break;
        }
    }
}

?>
