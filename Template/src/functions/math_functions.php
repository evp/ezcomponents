<?php
/**
 * File containing the ezcTemplateMathFunctions class
 *
 * @package Template
 * @version 1.4.2
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @access private
 */

/**
 * @package Template
 * @version 1.4.2
 * @access private
 */
class ezcTemplateMathFunctions extends ezcTemplateFunctions
{
    /**
     * Translates a function used in the Template language to a PHP function call.  
     * The function call is represented by an array with three elements:
     *
     * 1. The return typehint. Is it an array, a non-array, or both.
     * 2. The parameter input definition.
     * 3. The AST nodes.
     *
     * @param string $functionName
     * @param array(ezcTemplateAstNode) $parameters
     * @return array(mixed)
     */
    public static function getFunctionSubstitution( $functionName, $parameters )
    {
        switch ( $functionName )
        {
            // max( $v1 , $v2 [, ...] )::
            // max( $v1 , $v2 [, ...] )
            case "math_max": return [["%val", "%..."], self::functionCall( "max", ["%val", "%..."] )];

            // min( $v1 , $v2 [, ...] )::
            // min( $v1 , $v2 [, ...] )
            case "math_min": return [["%val", "%..."], self::functionCall( "min", ["%val", "%..."] )];

            // abs( $v )::
            // abs( $v )
            case "math_abs": return [["%val"], self::functionCall( "abs", ["%val"] )];

            // ceil( $v )::
            // ceil( $v )
            case "math_ceil": return [["%val"], self::functionCall( "ceil", ["%val"] )];

            // floor( $v )::
            // floor( $v )
            case "math_floor": return [["%val"], self::functionCall( "floor", ["%val"] )];

            // round( $v )::
            // round( $v )
            case "math_round": return [["%val"], self::functionCall( "round", ["%val"] )];

            // sqrt( $v )::
            // sqrt( $v )
            case "math_sqrt": return [["%val"], self::functionCall( "sqrt", ["%val"] )];

            // exp( $arg )::
            // exp( $arg )
            case "math_exp": return [["%arg"], self::functionCall( "exp", ["%arg"] )];

            // pow( $base, $exp )::
            // pow( $base, $exp )
            case "math_pow": return [["%base", "%exp"], self::functionCall( "pow", ["%base", "%exp"] )];

            // log( $arg, $base )::
            // log( $arg, $base )
            case "math_log": return [["%arg", "%base"], self::functionCall( "log", ["%arg", "%base"] )];

            // log10( $arg )::
            // log10( $arg )
            case "math_log10": return [["%arg"], self::functionCall( "log10", ["%arg"] )];

            // float_mod( $v )::
            // fmod( $v )
            case "math_float_mod": return [["%x", "%y"], self::functionCall( "fmod", ["%x", "%y"] )];

            // rand( $min, $max )::
            // mt_rand( $min, $max )
            case "math_rand": return [["%min", "%max"], self::functionCall( "mt_rand", ["%min", "%max"] )];

            // pi()::
            // pi()
            case "math_pi": return [[], self::functionCall( "pi", [] )];

            // is_finite( $v )::
            // is_finite( $v )
            case "math_is_finite": return [["%val"], self::functionCall( "is_finite", ["%val"] )];

            // is_infinite( $v )::
            // is_infinite( $v )
            case "math_is_infinite": return [["%val"], self::functionCall( "is_infinite", ["%val"] )];

            // is_nan( $v )::
            // is_nan( $v )
            case "math_is_nan": return [["%val"], self::functionCall( "is_nan", ["%val"] )];

            // bin_to_dec( $s )::
            // bindec( $s )
            case "math_bin_to_dec": return [["%string"], self::functionCall( "bindec", ["%string"] )];

            // hex_to_dec( $s )::
            // hexdec( $s )
            case "math_hex_to_dec": return [["%string"], self::functionCall( "hexdec", ["%string"] )];

            // oct_to_dec( $s )::
            // octdec( $s )
            case "math_oct_to_dec": return [["%string"], self::functionCall( "octdec", ["%string"] )];

            // dec_to_bin( $v )::
            // decbin( $v )
            case "math_dec_to_bin": return [["%val"], self::functionCall( "decbin", ["%val"] )];

            // dec_to_hex( $v )::
            // dechex( $v )
            case "math_dec_to_hex": return [["%val"], self::functionCall( "dechex", ["%val"] )];

            // dec_to_oct( $v )::
            // decoct( $v )
            case "math_dec_to_oct": return [["%val"], self::functionCall( "decoct", ["%val"] )];
        }

        return null;
    }
}
?>
