<?php
/**
 * File containing the ezcTemplateTypeFunctions class
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
class ezcTemplateTypeFunctions extends ezcTemplateFunctions
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
            // is_empty( $v )::
            // empty( $v )
            case "is_empty": return [["%var"], self::functionCall( "ezcTemplateType::is_empty", ["%var"] )];

            // is_array( $v )::
            // is_array( $v )
            case "is_array": return [["%var"], self::functionCall( "is_array", ["%var"] )];

            // is_bool( $v )::
            // is_bool( $v )
            case "is_bool": return [["%var"], self::functionCall( "is_bool", ["%var"] )];

            // is_float( $v )::
            // is_float( $v )
            case "is_float": return [["%var"], self::functionCall( "is_float", ["%var"] )];

            // is_int( $v )::
            // is_int( $v )
            case "is_int": return [["%var"], self::functionCall( "is_int", ["%var"] )];
                    
            // is_bool( $v )::
            // is_bool( $v )
            case "is_bool": return [["%var"], self::functionCall( "is_bool", ["%var"] )];


            // is_numeric( $v )::
            // is_numeric( $v )
            case "is_numeric": return [["%var"], self::functionCall( "is_numeric", ["%var"] )];

            // is_object( $v )::
            // is_object( $v ) ?
            case "is_object": return [["%var"], self::functionCall( "is_object", ["%var"] )];

            // is_class( $v, $class )::
            // getclass( $v ) == $class
            case "is_class": return [ezcTemplateAstNode::TYPE_VALUE, ["%var", "%class"], ["ezcTemplateIdenticalOperatorAstNode", [self::functionCall( "get_class", ["%var"] ), "%class"]]];

            // instanceof.
            case "is_instance": return [ezcTemplateAstnode::TYPE_VALUE, ["%var", "%class"], self::functionCall( "ezcTemplateType::is_instance", ["%var", "%class"] )];


            // is_scalar( $v )::
            // is_scalar( $v )
            case "is_scalar": return [["%var"], self::functionCall( "is_scalar", ["%var"] )];

            // is_string( $v )::
            // is_string( $v )
            case "is_string": return [["%var"], self::functionCall( "is_string", ["%var"] )];

            // is_set( $v )::
            // is_set( $v )
            case "is_set": return [["%var:Variable"], self::functionCall( "isset", ["%var:Variable"] )];

            // is_constant( $const )::
            // return defined( $const )
            case "is_constant": return [["%var"], self::functionCall( "defined", ["%var"] )];

            // get_constant( $const )::
            // constant( $const );
            case "get_constant": return [["%var"], self::functionCall( "constant", ["%var"] )];

            // get_class( $var )::
            // get_class( $var );
            case "get_class": return [["%var"], self::functionCall( "get_class", ["%var"] )];

            // cast_string( $v )::
            // (string)$v
            case "cast_string": return [["%var"], ["ezcTemplateTypeCastAstNode", ["string", "%var"]]];

            // cast_int( $v )::
            // (int)$v
            case "cast_int": return [["%var"], ["ezcTemplateTypeCastAstNode", ["int", "%var"]]];

            // cast_float( $v )::
            // (float)$v
            case "cast_float": return [["%var"], ["ezcTemplateTypeCastAstNode", ["float", "%var"]]];

        }

        return null;
    }
}
?>
