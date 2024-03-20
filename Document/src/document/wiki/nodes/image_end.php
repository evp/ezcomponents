<?php
/**
 * File containing the ezcDocumentWikiImageEndNode struct
 *
 * @package Document
 * @version 1.3.1
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Struct for Wiki document inline image end syntax tree nodes
 *
 * @package Document
 * @version 1.3.1
 */
class ezcDocumentWikiImageEndNode extends ezcDocumentWikiInlineNode
{
    /**
     * Set state after var_export
     *
     * @param array $properties
     * @return void
     * @ignore
     */
    public static function __set_state( $properties )
    {
        $nodeClass = self::class;
        $node = new $nodeClass( $properties['token'] );
        $node->nodes       = $properties['nodes'];
        return $node;
    }
}

?>
