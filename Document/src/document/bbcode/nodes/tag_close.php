<?php
/**
 * File containing the ezcDocumentBBCodeInlineNode struct
 *
 * @package Document
 * @version 1.3.1
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Struct for BBCode document inline abstract syntax tree nodes
 *
 * @package Document
 * @version 1.3.1
 */
class ezcDocumentBBCodeClosingTagNode extends ezcDocumentBBCodeNode
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
        $node->nodes = $properties['nodes'];
        return $node;
    }
}

?>
