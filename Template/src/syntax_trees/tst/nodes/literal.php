<?php
/**
 * File containing the ezcTemplateLiteralTstNode class
 *
 * @package Template
 * @version 1.4.2
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @access private
 */
/**
 * Builtin literal type value in an expression.
 *
 * @package Template
 * @version 1.4.2
 * @access private
 */
class ezcTemplateLiteralTstNode extends ezcTemplateExpressionTstNode
{

    /**
     * The value of the literal type.
     *
     * Note: This value contains null if it is not set yet, this means null is
     *       considered a literal type.
     * @var mixed
     */
    public $value;

    public const SINGLE_QUOTE = 1;
    public const DOUBLE_QUOTE = 2;

    public $quoteType;

    /**
     *
     * @param ezcTemplateSource $source
     * @param ezcTemplateCursor $start
     * @param ezcTemplateCursor $end
     */
    public function __construct( ezcTemplateSourceCode $source, /*ezcTemplateCursor*/ $start, /*ezcTemplateCursor*/ $end )
    {
        parent::__construct( $source, $start, $end );
        $this->value = null;
        $this->quoteType = null;
    }

    public function getTreeProperties()
    {
        return ['value' => $this->value, 'quoteType' => $this->quoteType];
    }
}
?>
