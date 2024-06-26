<?php
/**
 * File containing the ezcSearchDefinitionDocument class.
 *
 * @package Search
 * @version 1.0.9
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * The struct contains a document definition.
 *
 * @package Search
 * @version 1.0.9
 */
class ezcSearchDocumentDefinition
{
    /**
     * Type for string fields.
     */
    public const STRING = 1;

    /**
     * Type for text fields.
     */
    public const TEXT = 2;

    /**
     * Type for HTML fields.
     */
    public const HTML = 3;

    /**
     * Type for date fields.
     */
    public const DATE = 4;

    /**
     * Type for integer fields.
     */
    public const INT = 5;

    /**
     * Type for floating point fields.
     */
    public const FLOAT = 6;

    /**
     * Type for boolean fields.
     */
    public const BOOLEAN = 7;

    /**
     * Contains the document type - which is the same as the class name.
     *
     * @var string
     */
    public $documentType;

    /**
     * Contains the id property. This one is required.
     *
     * @var string
     */
    public $idProperty = null;

    /**
     * Contains the field name of the default search field.
     *
     * @var string
     */
    public $defaultField = null;

    /**
     * Contains an array of field definitions
     *
     * The array key also contains the name of the field
     *
     * @var array(string=>ezcSearchDefinitionDocumentField)
     */
    public $fields = [];

    /**
     * Creates a new ezcSearchDocumentDefinition for document type $documentType.
     *
     * @param string $documentType
     */
    public function __construct( $documentType )
    {
        $this->documentType = $documentType;
    }

    /**
     * Returns a list with all the field names
     *
     * @return array(string)
     */
    public function getFieldNames()
    {
        return array_keys( $this->fields );
    }

    /**
     * Returns all the field names that should appear in the search result
     *
     * @return array(string)
     */
    public function getSelectFieldNames()
    {
        $fields = [];
        foreach ( $this->fields as $name => $def )
        {
            if ( $def->inResult )
            {
                $fields[] = $name;
            }
        }
        return $fields;
    }

    /**
     * Returns all the field names that should appear in the highlighted fields
     *
     * @return array(string)
     */
    public function getHighlightFieldNames()
    {
        $fields = [];
        foreach ( $this->fields as $name => $def )
        {
            if ( $def->highlight )
            {
                $fields[] = $name;
            }
        }
        return $fields;
    }
}
?>
