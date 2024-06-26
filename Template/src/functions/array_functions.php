<?php
/**
 * File containing the ezcTemplateArrayFunctions class
 *
 * @package Template
 * @version 1.4.2
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @access private
 */

/**
 * This class contains a bundle of static functions, each implementing a specific
 * function used inside the template language. 
 * 
 * @package Template
 * @version 1.4.2
 * @access private
 */
class ezcTemplateArrayFunctions extends ezcTemplateFunctions
{
    /**
     * Translates a function used in the Template language to a PHP function call.  
     * The function call is represented by an array with three elements:
     *
     * 1. The return typehint. It is an array, a non-array, or both.
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
            // array_count( $a ) ( QList::count )::
            // count( $a )
            case "array_count": return [ezcTemplateAstNode::TYPE_VALUE, ["%array"], self::functionCall( "count", ["%array"] )];

            // array_contains( $a, $v ) ( QList::contains )::
            // in_array( $v, $a )
            case "array_contains": return [ezcTemplateAstNode::TYPE_VALUE, ["%array", "%value"], self::functionCall( "in_array", ["%value", "%array"] )];

            // array_is_empty( $a ) ( QList::isEmpty() )::
            // count( $a ) === 0
            case "array_is_empty": return [ezcTemplateAstNode::TYPE_VALUE, ["%array"], ["ezcTemplateIdenticalOperatorAstNode", [self::functionCall( "count", ["%array"] ), self::value( 0 )]]];

            // array_index_of( $a, $v ) ( QList::indexOf() )::
            // array_search( $v, $a )
            case "array_index_of": return [ezcTemplateAstNode::TYPE_VALUE, ["%array", "%value"], self::functionCall( "array_search", ["%value", "%array"] )];

            // array_index_exists( $a, $index ) ( QMap::find )::
            // array_key_exists( $index, $a )
            case "array_index_exists": return [ezcTemplateAstNode::TYPE_VALUE, ["%array", "%index"], self::functionCall( "array_key_exists", ["%index", "%array"] )];

            // array_left( $a, $len )::
            // array_slice( $a, 0, $len )
            case "array_left": return [ezcTemplateAstNode::TYPE_ARRAY, ["%array", "%length"], self::functionCall( "array_slice", ["%array", self::value( 0 ), "%length"] )];

            // array_right( $a, $len )::
            // array_slice( $a, 0, -$len )
            case "array_right": return [ezcTemplateAstNode::TYPE_ARRAY, ["%array", "%length"], self::functionCall( "array_slice", ["%array", self::value( 0 ), ["ezcTemplateArithmeticNegationOperatorAstNode", ["%length"]]] )];

            // array_mid( $a, $index, $len ) ( QValueList::mid )::
            // array_slice( $a, $index, $len )
            case "array_mid": return [ezcTemplateAstNode::TYPE_ARRAY, ["%array", "%index", "%length"], self::functionCall( "array_slice", ["%array", "%index", "%length"] )];

            // array_insert( $a, $index, $v1 [, $v2 ...] ) ( QList::insert() )::
            // array_slice( $a, 0, $index ) + array( $v1 [, $v2 ...] ) + array_slice( $a, $index + value count )
            case "array_insert": return [ezcTemplateAstNode::TYPE_ARRAY, ["%array", "%index", "%..."], self::functionCall( "array_merge", [self::functionCall( "array_slice", ["%array", self::value( 0 ), "%index"] ), self::functionCall( "array", ["%..."] ), self::functionCall( "array_slice", ["%array", "%index"] )] )];

            // array_append( $a, $v1 [, $v2 ...] ) ( QList::append() )::
            // Call user code.
            case "array_append": return [ezcTemplateAstNode::TYPE_ARRAY, ["%array", "%..."], self::functionCall( "ezcTemplateArray::array_append", ["%array", "%..."] )];

            // array_prepend( $a, $v1 [, $v2 ...] ) ( QList::prepend )::
            // array_unshift( $a, $v1 [, $v2 ...] )
            case "array_prepend": return [ezcTemplateAstNode::TYPE_ARRAY, ["%array", "%..."], self::functionCall( "ezcTemplateArray::array_prepend", ["%array", "%..."] )];

            // array_merge( $a1, $a2 [, $a3 ..] ) ( QList::+ )::
            // array_merge( $a1, $a2 [, $a3 ...] )
            case "array_merge": return [ezcTemplateAstNode::TYPE_ARRAY, ["%first", "%..."], self::functionCall( "array_merge", ["%first", "%..."] )];

            // array_remove( $a, $index, $len = 1 ) ( QList::remove )::
            // array_slice( $a, 0, $index ) + array_slice( $a, $index + $len )
            case "array_remove": 
                $length = ( self::countParameters( $parameters ) == 2 ? self::value( 1 ) : "[%length]" );
                return [ezcTemplateAstNode::TYPE_ARRAY, ["%array", "%index", "[%length]"], self::functionCall( "array_merge", [self::functionCall( "array_slice", ["%array", self::value( 0 ), "%index"] ), self::functionCall( "array_slice", ["%array", ["ezcTemplateAdditionOperatorAstNode", ["%index", $length]]] )] )];

            // array_remove_first( $a, $len = 1 ) ( QList::removeFirst() )::
            // array_slice( $a, 1 )
            case "array_remove_first": 
                $length = ( self::countParameters( $parameters ) == 1 ? self::value( 1 ) : "[%length]" );
                return [ezcTemplateAstNode::TYPE_ARRAY, ["%array", "[%length]"], self::functionCall( "array_slice", ["%array", $length] )];

            // array_remove_last( $a, $len = 1 ) ( QList::removeLast() )::
            // array_slice( $a, 0, -1 )
            case "array_remove_last": 
                $length = ( self::countParameters( $parameters ) == 1 ? self::value( 1 ) : "[%length]" );
                return [ezcTemplateAstNode::TYPE_ARRAY, ["%array", "[%length]"], self::functionCall( "array_slice", ["%array", self::value( 0 ), ["ezcTemplateArithmeticNegationOperatorAstNode", [$length]]] )];

            // array_first( $a ) ( QList::first )::
            // count( $a ) > 0 ? $a[0] : false
            // TODO, ? : 

            // array_last( $a ) ( QList::last )::
            // count( $a ) > 0 ? $a[count( $a ) - 1] : false
            // TODO, ? : 

            // array_replace( $a, $index, $len = 1, $v1 [, $v2 ...] ) ( QList::replace )::
            // array_slice( $a, 0, $index ) + array( $v1 [, $v2 ...] ) + array_slice( $a, $index + $len )
            case "array_replace": return [ezcTemplateAstNode::TYPE_ARRAY, ["%array", "%index", "%length", "%..."], self::functionCall( "array_merge", [self::functionCall( "array_slice", ["%array", self::value( 0 ), "%index"] ), self::functionCall( "array", ["%..."] ), self::functionCall( "array_slice", ["%array", ["ezcTemplateAdditionOperatorAstNode", ["%index", "%length"]]] )] )];

            // array_swap( $a, $index1, $index2 ) ( QList::swap ) ?::
            case "array_swap": return [ezcTemplateAstNode::TYPE_ARRAY, ["%array", "%index1", "%index2"], self::functionCall( "ezcTemplateArray::array_swap", ["%array", "%index1", "%index2"] )];
 
            // array_at( $a, $index ) ( QList::at )::
            // $a[$index]
            // TODO, $a cannot be an array definition.

            // array_reverse( $a )::
            // array_reverse( $a )
            case "array_reverse": return [ezcTemplateAstNode::TYPE_ARRAY, ["%array"], self::functionCall( "array_reverse", ["%array"] )];

            // array_diff( $a1, $a2 [, $a3 ...] )::
            // array_diff( $a1, $a2 [, $a3 ...] )
            case "array_diff": return [ezcTemplateAstNode::TYPE_ARRAY, ["%array1", "%array2", "[%...]"], self::functionCall( "array_diff", ["%array1", "%array2", "[%...]"] )];

            // array_intersect( $a1, $a2 [, $a3 ...] )::
            // array_intersect( $a1, $a2 [, $a3 ...] )
            case "array_intersect": return [ezcTemplateAstNode::TYPE_ARRAY, ["%array1", "%array2", "[%...]"], self::functionCall( "array_intersect", ["%array1", "%array2", "[%...]"] )];

            // array_pad( [$a = array(),] $len, $fill )::
            // array_pad( $a, $len, $fill )
            case "array_pad": 

               if ( self::countParameters( $parameters ) == 2 )
               {
                    return [ezcTemplateAstNode::TYPE_ARRAY, ["[%array]", "%length", "%pad"], self::functionCall( "array_pad", [self::functionCall( "array", [] ), "%length", "%pad"] )];
               }
               else
               {
                    return [ezcTemplateAstNode::TYPE_ARRAY, ["[%array]", "%length", "%pad"], self::functionCall( "array_pad", ["[%array]", "%length", "%pad"] )];
               }

            // array_unique( $a )::
            // array_unique( $a )
            case "array_unique": return [ezcTemplateAstNode::TYPE_ARRAY, ["%array"], self::functionCall( "array_unique", ["%array"] )];

            // array_find( $a, $v )::
            // array_search( $v, $a )
            case "array_find": return [["%array", "%value"], self::functionCall( "array_search", ["%value", "%array"] )];

            // array_find_replace( $a, $v, $vNew )::
            // $key = array_search( $v, $a ); if ( $key ) $a[$key] = $vNew;
            case "array_find_replace": return [["%array", "%find", "%replace"], self::functionCall( "ezcTemplateArray::array_find_replace", ["%array", "%find", "%replace"] )];

            // array_range( $low, $high [, $step] )::
            // array_range( $low, $high [, $step] )
            case "array_fill_range": return [ezcTemplateAstNode::TYPE_ARRAY, ["%low", "%high", "[%step]"], self::functionCall( "range", ["%low", "%high", "[%step]"] )];

            // array_sum( $a )::
            // array_sum( $a )
            case "array_sum": return [ezcTemplateAstNode::TYPE_VALUE, ["%array"], self::functionCall( "array_sum", ["%array"] )];

            //     array_extract_by_properties( $a, $pList )::
            // TODO Needs testing.
            case "array_extract_by_properties": return [["%array", "%properties"], self::functionCall( "ezcTemplateArray::array_extract_by_properties", ["%array", "%properties"] )];

            //    array_extract_by_keys( $a, $kList )::
            // 
            //    array_sum( array_extract_by_keys( $order.items, array( 'price' ) ) )
            // 
            //    becomes
            // 
            //    foreach ( $order->items as $item )
            //    {
            //        $list[] = $item['price'];
            //    }
            //    array_sum( $list )
            //    unset( $list 
            // TODO


            // array_fill( $v, $len ) ( QVector::fill )::
            // array_fill( 0, $len, $v )
            // Skipped, can be done with array_pad.
            
            // array_repeat( $asrc, $len ) ( QVector::fill )::
            // $aout = array(); for ( $i = 0; $i < $len; ++$i ) { $aout += $a; }
            case "array_repeat": return [["%array", "%length"], self::functionCall( "ezcTemplateArray::array_repeat", ["%array", "%length"] )];


            case "array_sort": return [["%array"], self::functionCall( "ezcTemplateArray::array_sort", ["%array"] )];

            case "array_sort_reverse": return [["%array"], self::functionCall( "ezcTemplateArray::array_sort_reverse", ["%array"] )];

            case "hash_sort": return [["%array"], self::functionCall( "ezcTemplateArray::hash_sort", ["%array"] )];

            case "hash_sort_reverse": return [["%array"], self::functionCall( "ezcTemplateArray::hash_sort_reverse", ["%array"] )];

            case "hash_sort_keys": return [["%array"], self::functionCall( "ezcTemplateArray::hash_sort_keys", ["%array"] )];

            case "hash_sort_keys_reverse": return [["%array"], self::functionCall( "ezcTemplateArray::hash_sort_keys_reverse", ["%array"] )];

            // hash_diff( $a1, $a2 [, $a3 ...] )::
            // array_diff_assoc( $a1, $a2 [, $a3 ...] )
            case "hash_diff": return [["%array1", "%array2", "[%...]"], self::functionCall( "array_diff_assoc", ["%array1", "%array2", "[%...]"] )];

            // hash_diff_key( $a1, $a2 [, $a3 ...] )::
            // array_diff_key( $a1, $a2 [, $a3 ...] )
            case "hash_diff_key": return [["%array1", "%array2", "[%...]"], self::functionCall( "array_diff_key", ["%array1", "%array2", "[%...]"] )];

            // hash_intersect( $a1, $a2 [, $a3 ...] )::
            // array_intersect_assoc( $a1, $a2 [, $a3 ...] )
            case "hash_intersect": return [["%array1", "%array2", "[%...]"], self::functionCall( "array_intersect_assoc", ["%array1", "%array2", "[%...]"] )];

            // hash_intersect_key( $a1, $a2 [, $a3 ...] )::
            // array_intersect( $a1, $a2 [, $a3 ...] )
            case "hash_intersect_key": return [["%array1", "%array2", "[%...]"], self::functionCall( "array_intersect_key", ["%array1", "%array2", "[%...]"] )];

            // hash_keys( $a ) ( QMap::keys )::
            // array_keys( $a )
            case "hash_keys": return [["%array"], self::functionCall( "array_keys", ["%array"] )];

            // hash_values( $a )::
            // array_values( $a )
            case "hash_values": return [["%array"], self::functionCall( "array_values", ["%array"] )];

            // hash_flip( $a )::
            // array_flip( $a )
            case "hash_flip": return [["%array"], self::functionCall( "array_flip", ["%array"] )];
        }

        return null;
    }
}
?>
