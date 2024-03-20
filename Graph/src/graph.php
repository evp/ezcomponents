<?php
/**
 * File containing the abstract ezcGraph class
 *
 * @package Graph
 * @version 1.5
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */
/**
 * Base options class for all eZ components.
 *
 * @version 1.5
 * @package Graph
 */
class ezcGraph
{
    /**
     * No symbol, will fallback to a rect in the legend
     */
    public const NO_SYMBOL = 0;
    /**
     * Rhomb like looking symbol
     */
    public const DIAMOND = 1;
    /**
     * Filled circle
     */
    public const BULLET = 2;
    /**
     * Non filled circle
     */
    public const CIRCLE = 3;
    /**
     * Arrow head symbol, used for axis end markers, not available as a dataset
     * symbol.
     */
    public const ARROW = 4;
    /**
     * A square, filled box, symbol
     */
    public const SQUARE = 5;
    /**
     * A non-filled box symbol
     */
    public const BOX = 6;

    /**
     * Constant used for background repetition. No repeat.
     */
    public const NO_REPEAT = 0;
    /**
     * Constant used for background repetition. Repeat along the x axis. May be
     * used as a bitmask together with ezcGraph::VERTICAL.
     */
    public const HORIZONTAL = 1;
    /**
     * Constant used for background repetition. Repeat along the y axis. May be
     * used as a bitmask together with ezcGraph::HORIZONTAL.
     */
    public const VERTICAL = 2;

    /**
     * Constant used for positioning of elements. May be used as a bitmask 
     * together with other postioning constants.
     * Element will be placed at the top of the current boundings.
     */
    public const TOP = 1;
    /**
     * Constant used for positioning of elements. May be used as a bitmask 
     * together with other postioning constants.
     * Element will be placed at the bottom of the current boundings.
     */
    public const BOTTOM = 2;
    /**
     * Constant used for positioning of elements. May be used as a bitmask 
     * together with other postioning constants.
     * Element will be placed at the left of the current boundings.
     */
    public const LEFT = 4;
    /**
     * Constant used for positioning of elements. May be used as a bitmask 
     * together with other postioning constants.
     * Element will be placed at the right of the current boundings.
     */
    public const RIGHT = 8;
    /**
     * Constant used for positioning of elements. May be used as a bitmask 
     * together with other postioning constants.
     * Element will be placed at the horizontalcenter of the current boundings.
     */
    public const CENTER = 16;
    /**
     * Constant used for positioning of elements. May be used as a bitmask 
     * together with other postioning constants.
     * Element will be placed at the vertical middle of the current boundings.
     */
    public const MIDDLE = 32;

    /**
     * Display type for datasets. Pie may only be used with pie charts. 
     */
    public const PIE = 1;
    /**
     * Display type for datasets. Bar and line charts may contain datasets of
     * type ezcGraph::LINE.
     */
    public const LINE = 2;
    /**
     * Display type for datasets. Bar and line charts may contain datasets of
     * type ezcGraph::BAR.
     */
    public const BAR = 3;
    /**
     * @TODO:
     */
    public const ODOMETER = 4;

    /**
     * Font type definition. Used for True Type fonts.
     */
    public const TTF_FONT = 1;
    /**
     * Font type definition. Used for Postscript Type 1 fonts.
     */
    public const PS_FONT = 2;
    /**
     * Font type definition. Used for Palm Format Fonts for Ming driver.
     */
    public const PALM_FONT = 3;
    /**
     * Font type definition. Used for SVG fonts vonverted by ttf2svg used in
     * the SVG driver.
     */
    public const SVG_FONT = 4;

    /**
     * Identifier for keys in complex dataset arrays
     */
    public const KEY = 0;
    /**
     * Identifier for values in complex dataset arrays
     */
    public const VALUE = 1;
}

?>
