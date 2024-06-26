<?php
/**
 * File containing the ezcDocumentPdfHyphenator class
 *
 * @package Document
 * @version 1.3.1
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @access private
 */

/**
 * Default hyphenation implementation, which does no word splitting at all.
 *
 * @package Document
 * @access private
 * @version 1.3.1
 */
class ezcTestDocumentPdfHyphenator extends ezcDocumentPdfHyphenator
{
    /**
     * Split word into hypens
     *
     * Takes a word as a string and should return an array containing arrays of
     * two words, which each represent a possible split of a word. The german
     * word "Zuckerstück" for example changes its hyphens depending on the
     * splitting point, so the return value would look like:
     *
     * <code>
     *  array(
     *      array( 'Zuk-', 'kerstück' ),
     *      array( 'Zucker-', 'stück' ),
     *  )
     * </code>
     *
     * You should always also include the concatenation character in the split
     * words, since it might change depending on the used language.
     * 
     * @param mixed $word 
     * @return void
     */
    public function splitWord( $word )
    {
        $splits = [];
        for ( $i = 1; $i < iconv_strlen( $word, 'UTF-8' ); ++$i )
        {
            $splits[] = [iconv_substr( $word, 0, $i ) . '-', iconv_substr( $word, $i )];
        }
        return $splits;
    }
}
?>
