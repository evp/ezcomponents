<?php
/**
 * File containing the ezcSearchQueryToken class.
 *
 * @package Search
 * @version 1.0.9
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * The struct contains tokens that make up a search query
 *
 * @package Search
 * @version 1.0.9
 */
class ezcSearchQueryToken
{
    public const STRING = 1;
    public const SPACE  = 2;
    public const QUOTE  = 3;
    public const PLUS   = 4;
    public const MINUS  = 5;
    public const BRACE_OPEN  = 6;
    public const BRACE_CLOSE = 7;
    public const LOGICAL_AND = 8;
    public const LOGICAL_OR  = 9;
    public const COLON  = 10;

    /**
     * Token type
     *
     * @var int
     */
    public $type;

    /**
     * Token contents
     *
     * @var string
     */
    public $token;

    /**
     * Contructs a new ezcSearchResult.
     *
     * @param int $type
     * @param string $token
     */
    public function __construct( $type, $token )
    {
        $this->type = $type;
        $this->token = $token;
    }

    /**
     * Returns a new instance of this class with the data specified by $array.
     *
     * $array contains all the data members of this class in the form:
     * array('member_name'=>value).
     *
     * __set_state makes this class exportable with var_export.
     * var_export() generates code, that calls this method when it
     * is parsed with PHP.
     *
     * @param array(string=>mixed) $array
     * @return ezcSearchResult
     */
    static public function __set_state( array $array )
    {
        return new ezcSearchResult( $array['type'], $array['token'] );
    }
}
?>
