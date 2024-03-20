<?php
/**
 * File containing the ezcTemplateStringFunctions class
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
class ezcTemplateStringFunctions extends ezcTemplateFunctions
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
            // str_replace( $sl, $index, $len, $sr )
            // substr( $sl, 0, $index ) . $sr . substr( $sl, $index + $len );
            case "str_replace": 
                return [ezcTemplateAstNode::TYPE_VALUE, ["%left", "%index", "%length", "%right"], self::concat( 
                    self::functionCall( "substr", ["%left", self::value( 0 ), "%index"] ),
                    self::concat( 
                        "%right", 
                        self::functionCall( 
                            "substr", 
                            ["%left", ["ezcTemplateAdditionOperatorAstNode", ["%index", "%length"]]] 
                        ) 
                    ) 
                 )]; 


            // str_remove( $s, $index, $len ) 
            // substr( $s, 0, $index ) . substr( $s, $index + $len );
             case "str_remove": 
                 return [ezcTemplateAstNode::TYPE_VALUE, ["%string", "%index", "%length"], self::concat( 
                     self::functionCall( "substr", ["%string", self::value( 0 ), "%index"] ),
                     self::functionCall( "substr", ["%string", ["ezcTemplateAdditionOperatorAstNode", ["%index", "%length"]]] 
                         ) 
                      )];
  
            // string str_chop( $s, $len ) ( QString::chop ):
            // substr( $s, 0, strlen( $string ) - $len );
             case "str_chop": 
                return [ezcTemplateAstNode::TYPE_VALUE, ["%string", "%length"], self::functionCall( "substr", ["%string", self::value( 0 ), ["ezcTemplateSubtractionOperatorAstNode", [self::functionCall( "strlen", ["%string"] ), "%length"]]]
            )];
                       
            // string str_chop_front( $s, $len )
            // substr( $s, $len );
             case "str_chop_front": return [ezcTemplateAstNode::TYPE_VALUE, ["%string", "%length"], self::functionCall( "substr", ["%string", "%length"] )];

             case "str_append": return [ezcTemplateAstNode::TYPE_VALUE, ["%left", "%right"], self::concat( "%left", "%right" )];

             case "str_prepend": return [ezcTemplateAstNode::TYPE_VALUE, ["%left", "%right"], self::concat( "%right", "%left" )];

            // str_compare( $sl, $sr )
            // strcmp( $sl, $sr );
            case "str_compare": return [ezcTemplateAstNode::TYPE_VALUE, ["%left", "%right"], self::functionCall( "strcmp", ["%left", "%right"] )];

            // str_nat_compare( $sl, $sr )
            // strnatcmp( $sl, $sr );
            case "str_nat_compare": return [ezcTemplateAstNode::TYPE_VALUE, ["%left", "%right"], self::functionCall( "strnatcmp", ["%left", "%right"] )];

            // str_contains( $sl, $sr ) ( QString::compare )::
            // strpos( $sl, $sr ) !== false 
            case "str_contains": return [ezcTemplateAstNode::TYPE_VALUE, ["%left", "%right"], ["ezcTemplateNotIdenticalOperatorAstNode", [self::functionCall( "strpos", ["%left", "%right"] ), self::value( false )]]];

            // str_len( $s )
            // strlen( $s )
            case "str_len": return [ezcTemplateAstNode::TYPE_VALUE, ["%string"], self::functionCall( "strlen", ["%string"] )];

            // str_left( $s, $len )
            // substr( $s, 0, $len )
            case "str_left": return [ezcTemplateAstNode::TYPE_VALUE, ["%string", "%length"], self::functionCall( "substr", ["%string", self::value( 0 ), "%length"] )];

            // str_starts_with( $sl, $sr )
            // strpos( $sl, $sr ) === 0
            case "str_starts_with": return [ezcTemplateAstNode::TYPE_VALUE, ["%haystack", "%needle"], ["ezcTemplateIdenticalOperatorAstNode", [self::functionCall( "strpos", ["%haystack", "%needle"] ), self::value( 0 )]]]; 

            // str_right( $s, $len )
            // substr( $s, -$len )
            case "str_right": return [ezcTemplateAstNode::TYPE_VALUE, ["%string", "%length"], self::functionCall( "substr", ["%string", ["ezcTemplateArithmeticNegationOperatorAstNode", ["%length"]]] )];

            // str_ends_with( $sl, $sr )
            // strrpos( $sl, $sr ) === ( strlen( $sl ) - strlen( $sr) )
            case "str_ends_with": return [ezcTemplateAstNode::TYPE_VALUE, ["%haystack", "%needle"], ["ezcTemplateIdenticalOperatorAstNode", [self::functionCall( "strrpos", ["%haystack", "%needle"] ), ["ezcTemplateSubtractionOperatorAstNode", [self::functionCall( "strlen", ["%haystack"] ), self::functionCall( "strlen", ["%needle"] )]]]]]; 

            // str_mid( $s, $index, $len )
            // substr( $s, $index, $len )
            case "str_mid": return [ezcTemplateAstNode::TYPE_VALUE, ["%string", "%index", "%length"], self::functionCall( "substr", ["%string", "%index", "%length"] )];

            // str_at( $s, $index )
            // substr( $s, $index, 1 )
            case "str_at": return [ezcTemplateAstNode::TYPE_VALUE, ["%string", "%index"], self::functionCall( "substr", ["%string", "%index", self::value( 1 )] )];

            // str_fill( $s, $len )
            // str_repeat( $s, $len )
            case "str_fill": return [ezcTemplateAstNode::TYPE_VALUE, ["%string", "%length"], self::functionCall( "str_repeat", ["%string", "%length"] )];

            // str_index_of( $sl, $sr [, $index ] )
            // strpos( $sl, $sr [, $index ] )
            case "str_index_of": return [ezcTemplateAstNode::TYPE_VALUE, ["%haystack", "%needle", "[%index]"], self::functionCall( "strpos", ["%haystack", "%needle", "[%index]"] )];
            
            // str_last_index( $sl, $sr [, $index] )
            // strrpos( $sl, $sr [, $index ] )
            case "str_last_index": return [ezcTemplateAstNode::TYPE_VALUE, ["%haystack", "%needle", "[%index]"], self::functionCall( "strrpos", ["%haystack", "%needle", "[%index]"] )];
             
            // str_is_empty( $s )
            // strlen( $s ) === 0
            case "str_is_empty": return [ezcTemplateAstNode::TYPE_VALUE, ["%string"], ["ezcTemplateIdenticalOperatorAstNode", [self::functionCall( "strlen", ["%string"] ), self::value( 0 )]]];
             
            // str_pad_left( $s, $len, $fill )
            // str_pad( $s, $len, $fill, STR_PAD_LEFT )
            case "str_pad_left": return [ezcTemplateAstNode::TYPE_VALUE, ["%string", "%length", "%fill"], self::functionCall( "str_pad", ["%string", "%length", "%fill", self::constant( "STR_PAD_LEFT" )] )];
             
            // str_pad_right( $s, $len, $fill ) ( QString::rightJustified() )::
            // str_pad( $s, $len, $fill, STR_PAD_RIGHT )
            case "str_pad_right": return [ezcTemplateAstNode::TYPE_VALUE, ["%string", "%length", "%fill"], self::functionCall( "str_pad", ["%string", "%length", "%fill", self::constant( "STR_PAD_RIGHT" )] )];
             
            // str_number( $num, $decimals, $point, $sep )
            // number_format( $num, $decimals, $point, $sep )
            case "str_number": return [ezcTemplateAstNode::TYPE_VALUE, ["%number", "%decimals", "%point", "%separator"], self::functionCall( "number_format", ["%number", "%decimals", "%point", "%separator"] )];
             
            // str_trim( $s [, $chars ] )
            // trim( $s [, $chars] )
            case "str_trim": return [ezcTemplateAstNode::TYPE_VALUE, ["%string", "[%chars]"], self::functionCall( "trim", ["%string", "[%chars]"] )];
             
            // str_trim_left( $s [, $chars] )
            // ltrim( $s [, $chars] )
            case "str_trim_left": return [ezcTemplateAstNode::TYPE_VALUE, ["%string", "[%chars]"], self::functionCall( "ltrim", ["%string", "[%chars]"] )];
             
            // str_trim_right( $s [, $chars] )
            // rtrim( $s, [$chars] )
            case "str_trim_right": return [ezcTemplateAstNode::TYPE_VALUE, ["%string", "[%chars]"], self::functionCall( "rtrim", ["%string", "[%chars]"] )];
             
            // str_simplified( $s )
            // trim( preg_replace( "/(\n|\t|\r\n|\s)+/", " ", $s ) )
            case "str_simplify": return [ezcTemplateAstNode::TYPE_VALUE, ["%string"], self::functionCall( "trim", [self::functionCall( "preg_replace", [self::constant( '"/(\n|\t|\r\n|\s)+/"' ), self::value( " " ), "%string"] )] )];
             
            // str_split( $s, $sep[, $max] )
            // explode( $s, $sep, $max )
            case "str_split": return [ezcTemplateAstNode::TYPE_VALUE | ezcTemplateAstNode::TYPE_ARRAY, ["%string", "%separator", "[%max]"], self::functionCall( "explode", ["%separator", "%string", "[%max]"] )];
             
            // str_join( $s_list, $sep )
            // join( $sList, $sep )
            case "str_join": return [ezcTemplateAstNode::TYPE_VALUE, ["%list", "%separator"], self::functionCall( "join", ["%separator", "%list"] )];
             
            // str_printf( $format [...] )
            // sprintf( $format [...] )
            // TODO
             
            // str_chr( $ord1 [, $ord2...] )::
            // ord( $ord1 ) [ . ord( $ord2 ) ...]
            // TODO 
            
            // str_ord( $c )
            // ord( $c )
            case "str_ord": return [ezcTemplateAstNode::TYPE_VALUE, ["%char"], self::functionCall( "ord", ["%char"] )];

            // chr( $c )
            case "str_chr": return [ezcTemplateAstNode::TYPE_VALUE, ["%char"], self::functionCall( "chr", ["%char"] )];
            
            // str_ord_list( $s )::
            // chr( $s[0] ) [ . chr( $s[1] ) ]
            // TODO
             
            // str_upper( $s )
            // strtoupper( $s )
            case "str_upper": return [ezcTemplateAstNode::TYPE_VALUE, ["%string"], self::functionCall( "strtoupper", ["%string"] )];
            
            // str_lower( $s )
            // strtolower( $s )
            case "str_lower": return [ezcTemplateAstNode::TYPE_VALUE, ["%string"], self::functionCall( "strtolower", ["%string"] )];
             
            // str_capitalize( $s )::
            // ucfirst( $s )
            case "str_capitalize": return [ezcTemplateAstNode::TYPE_VALUE, ["%string"], self::functionCall( "ucfirst", ["%string"] )];
             
            // str_find_replace( $s, $find, $replace, $count )::
            // str_replace( $s, $replace, $find, $count )
            case "str_find_replace": return [ezcTemplateAstNode::TYPE_VALUE, ["%string", "%find", "%replace", "[%count]"], self::functionCall( "str_replace", ["%find", "%replace", "%string", "[%count]"] )];
             
            // str_reverse( $s )::
            // strrev( $s )
            case "str_reverse": return [ezcTemplateAstNode::TYPE_VALUE, ["%string"], self::functionCall( "strrev", ["%string"] )];
             
            // str_section( $s, $sep, $start, $end = -1 )
            // join( array_slice( split( $s, $sep, $end != -1 ? $end, false ), $start, $end ? $end : false ) )
            // TODO

             // str_char_count( $s )::
            // strlen( $s )
            case "str_char_count": return [ezcTemplateAstNode::TYPE_VALUE, ["%string"], self::functionCall( "strlen", ["%string"] )];
             
            // str_word_count( $s [, $wordsep] )
            // str_word_count( $s, 0 [, $wordsep] )
            case "str_word_count": return [ezcTemplateAstNode::TYPE_VALUE, ["%string", "[%wordsep]"], self::functionCall( "str_word_count", ["%string", self::value( 0 ), "[%wordsep]"] )];
 
            // - *string* str_paragraph_count( $s )::
            // Code.
            case "str_paragraph_count": return [ezcTemplateAstNode::TYPE_VALUE, ["%string"], self::functionCall( "ezcTemplateString::str_paragraph_count", ["%string"] )];
 
           // 
            // - *string* str_sentence_count( $s )::
            // 
            //     $pos = 0;
            //     $count = 0;
            //     while ( preg_match( "/. /", $s, $m, PREG_OFFSET_CAPTURE, $pos )
            //     {
            //         ++$count;
            //         $pos = $m[0][1];
            //     }
            // TODO
            // 
            // - *string* str_break( $s, $eol = contextaware, $lbreak = contextaware )::
            // 
            //     str_replace( context_eol_char( $eol ), context_linebreak_char( $eol ), $s )
            // 
            // TODO
            // 
            // - *string* str_break_chars( $s, $cbreak )::
            // 
            //     $sNew = '';
            //     for ( $i = 0; $i < strlen( $s ) - 1; ++$i )
            //     {
            //         $sNew .= $s[$i] . $cbreak;
            //     }
            //     $sNew .= $s[strlen( $s ) - 1];
            // 
            // TODO
            
            // str_wrap( $s, $width, $break, $cut )
            // wordwrap( $s, $width, $break, $cut )
            case "str_wrap": return [ezcTemplateAstNode::TYPE_VALUE, ["%string", "%width", "%break", "[%cut]"], self::functionCall( "wordwrap", ["%string", "%width", "%break", "[%cut]"] )];

            // base64_encode( $s )
            case "str_base64_encode":
                return [ezcTemplateAstNode::TYPE_VALUE, ["%string"], self::functionCall( "base64_encode", ["%string"] )];
            
            // base64_decode( $s )
            case "str_base64_decode":
                return [ezcTemplateAstNode::TYPE_VALUE, ["%string"], self::functionCall( "base64_decode", ["%string"] )];
 
            // 
            // - *string* str_wrap_indent::
            // 
            //    $tmp = wordwrap( $s, $width, $break, $cut )
            //    $lines = explode( "\n", $tmp );
            //    $newLines = array();
            //    foreach ( $lines as $line )
            //    {
            //        $newLines[] = $prefix . $line . $suffix;
            //    }
            //    return join( "\n", $newLines )
            // 
            // TODO
            // - *string* str_block( $s, $prefix, $suffix )
            // 
            // 
            // - *string* str_shorten_right( $s, $max_size )
            // 
            // - *string* str_shorten_mid( $s, $max_size )
            // 
            // - *string* str_shorten_left( $s, $max_size )
            // 
            // - *string* str_crc32( $s )::
            // 
            //     crc32( $s )
            // 
            // - *string* str_md5( $s )::
            // 
            //     md5( $s )
            // 
            // - *string* str_sha1( $s )::
            // 
            //     sha1( $s )
            // 
            // - *string* str_rot13( $s )::
            // 
            //     str_rot13( $s )
            // 
            // Some of the functions are also available as case insensitive versions, they are:
            // 
            // - *string* stri_contains( $sl, $sr ) ( QString::compare )::
            // 
            //     stristr( $sl, $sr ) !== false
            // 
            // - *string* stri_starts_with( $sl, $sr ) ( QString::startsWith )::
            // 
            //     stripos( strtolower( $sl ), strtolower( $sr ) ) === 0
            // 
            // - *string* stri_ends_with( $sl, $sr ) ( QString::endsWith )::
            // 
            //     strripos( $sl, $sr ) === ( strlen( $sl ) - 1 )
            // 
            // - *string* stri_index( $sl, $sr [, $from] ) ( QString::indexOf )::
            // 
            //     stripos( $sl, $sr [, $from ] )
            // 
            // - *string* stri_last_index( $sl, $sr [, $from] ) ( QString::lastIndexOf )::
            // 
            //     strirpos( $sl, $sr [, $from ] )
            // 
            // - *string* stri_find_replace( $s, $find, $replace, $count )::
            // 
            //     str_ireplace( $s, $replace, $find, $count )
            // 
            // - *string* stri_compare( $sl, $sr ) ( QString::compare )::
            // 
            //     strcasecmp( $sl, $sr );
            // 
            // - *string* stri_nat_compare( $sl, $sr )::
            // 
            //     strnatcasecmp( $sl, $sr );
            // 

        }

        return null;
    }
}
?>
