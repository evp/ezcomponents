<?php
/**
 * File containing the ezcTemplateSourceToTstErrorMessages
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
class ezcTemplateSourceToTstErrorMessages
{
    // Expected types
    public const MSG_EXPECT_ARROW_OR_CLOSE_CURLY_BRACKET = "Expecting the keyword '=>' or closing curly bracket '}'";
    public const MSG_EXPECT_AS                         = "Expecting the keyword 'as'.";
    public const MSG_EXPECT_CASE_STATEMENT             = "Expecting an case block.";
    public const MSG_EXPECT_DELIMITER_INSIDE_FOREACH   = "Delimiter can only be used inside a foreach block.";
    public const MSG_EXPECT_EXPRESSION                 = "Expecting an expression.";
    public const MSG_EXPECT_EXPRESSION_NOT_IDENTIFIER  = "Expecting an expression, not an identifier. (Braces missing?)";
    public const MSG_EXPECT_OPERAND                    = "Expecting an operand.";
    public const MSG_EXPECT_LITERAL                    = "Expecting a literal value.";
    public const MSG_EXPECT_STRING                     = "Expecting a string.";
    public const MSG_EXPECT_MODULO                     = "Expecting a modulo";
    public const MSG_EXPECT_NON_MODIFYING_OPERAND      = "Expecting an operand without a pre- or post operator.";
    public const MSG_EXPECT_VARIABLE                   = "Expecting a variable";

    public const MSG_EXPECT_VALUE                      = "Expected two operands that are not an array.";

    // Unexpected types
    public const MSG_UNEXPECTED_TOKEN                  = "Unexpected token: %s";
    public const MSG_UNEXPECTED_BREAK_OR_CONTINUE      = "Cannot break or continue outside a loop.";

    // Invalid
    public const MSG_INVALID_VARIABLE_NAME             = "The variable name is invalid.";
    public const MSG_INVALID_OPERATOR_ON_CYCLE         = "This operator cannot be used on a cycle.";
    public const MSG_INVALID_IDENTIFIER                = "Invalid identifier";


    //  expected brackets
    public const MSG_EXPECT_CURLY_BRACKET_OPEN         = "Expecting an opening curly bracket: '{'";
    public const MSG_EXPECT_CURLY_BRACKET_CLOSE        = "Expecting a closing curly bracket: '}'";
    public const MSG_EXPECT_ROUND_BRACKET_OPEN         = "Expecting an opening parentheses: '('";
    public const MSG_EXPECT_ROUND_BRACKET_CLOSE        = "Expecting a closing parentheses: ')'";

    public const MSG_EXPECT_SQUARE_BRACKET_CLOSE        = "Expecting a closing square bracket: ']'";

    public const MSG_EXPECT_ROUND_BRACKET_CLOSE_OR_COMMA  = "Expecting a closing parentheses: ')' or a comma ','";

    // Unexpected brackets
    public const MSG_UNEXPECTED_SQUARE_BRACKET_OPEN    = "Unexpected opening square bracket '['. Array fetch needs a variable. ( \$variable [ 0 ] )";
    public const MSG_UNEXPECTED_ARRAY_APPEND           = "Unexpected array append '[]'. Did you forget an expression between the brackets?";
    public const MSG_EXPECT_ARRAY_APPEND_ASSIGNMENT    = "Expecting an assignment '=' after an array append '[]'.";


    public const MSG_ASSIGNMENT_NOT_ALLOWED            = "A 'raw' block cannot modify a variable.";

    // Uppercase problems
    public const MSG_ARRAY_NOT_LOWERCASE               = "The array identifier must consist of lowercase characters only.";
    public const MSG_BOOLEAN_NOT_LOWERCASE             = "Boolean type must use lowercase characters only.";

    // Other
    public const MSG_TYPEHINT_FAILURE                  = "The types (array or value) are not correctly used with this operator.";

    public const MSG_LHS_IS_NOT_VARIABLE               = "Unexpected operator '%s'. The left hand side must be variable.";
    public const MSG_LHS_IS_NOT_VARIABLE_NO_SYMBOL     = "The left hand side must be variable.";

    public const MSG_EXPECT_IDENTIFIER_OR_VARIABLE     = "Expecting an identifier or a variable.";
    public const MSG_EXPECT_ARRAY                      = "Expecting an array.";
    public const MSG_EXPECT_VALUE_NOT_ARRAY            = "Expecting a value and not an array.";
    public const MSG_PARAMETER_EXPECTS_EXPRESSION      = "Parameter %s expects a value.";
    public const MSG_EXPECT_ASSIGNMENT                 = "Expecting the assignment operator '='.";

    public const MSG_CONTEXT_DUPLICATE                 = "Expecting a closing curly bracket: '}' ('context' can be available only once in a tr statement).";
    public const MSG_COMMENT_DUPLICATE                 = "Expecting a closing curly bracket: '}' ('comment' can be available only once in a tr statement).";
    public const MSG_NO_TRANSLATION_CONTEXT            = "Expecting a 'context' parameter, or a default context set with {tr_context}.";

    public const MSG_DEFAULT_DUPLICATE                 = "Expecting {/switch}. ('default' can be available only once in the switch)";
    public const MSG_DEFAULT_LAST                      = "Expecting {/switch}. ('default' is expected to be the last case of the switch.)";

    public const MSG_UNKNOWN_BLOCK                     = "Unknown block '%s'.";
    public const MSG_UNKNOWN_FUNCTION                  = "Unknown function call: '%s'";
    public const MSG_EXPECT_PARAMETER                  = "Function call: '%s' has not enough parameters. Need an additional '%s' parameter.";
    public const MSG_TOO_MANY_PARAMETERS               = "Function call: '%s' has too many parameters.";
    public const MSG_NAMED_PARAMETER_NOT_FOUND         = "Could not find the named parameter: %s in the function: %s.";

    public const MSG_INVALID_DEFINITION_PARAMETER_OPTIONAL = "Parameter %s is optional in the CustomFunction definition but required in the declared function.";
    public const MSG_INVALID_DEFINITION_PARAMETER_DOES_NOT_EXIST = "Parameter %s is specified in the CustomFunction definition but not in the declared function.";
    public const MSG_INVALID_DEFINITION_EXPECT_OPTIONAL_PARAMETER  = "The definition has an optional parameter before the required parameters.";

    public const MSG_UNEXPECTED_BLOCK                   = "Unexpected block {%s} at this position. Some blocks can only be used inside other blocks.";
    public const MSG_OPERATOR_LHS_IS_MODIFYING_BLOCK   = "Unexpected operator. The left side of this expression is not allowed to modify a variable.";
    public const MSG_OPERATOR_RHS_IS_MODIFYING_BLOCK   = "Unexpected operand or expression. An operand or expression that modifies a variable cannot be used in combination with the '%s' operator.";
    public const MSG_OPERATOR_IS_MODIFYING_BLOCK       = "Unexpected operand or expression. The operand or expression modifies a variable, which is not allowed.";

    public const MSG_PARAMETER_CANNOT_BE_MODIFYING_BLOCK = "The given parameter is not allowed to modify a variable.";
    public const MSG_MODIFYING_EXPRESSION_NOT_ALLOWED   = "An expression that modifies a variable is not allowed.";


    public const MSG_OBJECT_FUNCTION_CALL_NOT_ALLOWED  = "Calling a method from an imported object is not allowed.";

    public const MSG_MISSING_CUSTOM_BLOCK_PARAMETER     = "Missing the required custom block parameter '%s'."; 
    public const MSG_UNKNOWN_CUSTOM_BLOCK_PARAMETER     = "Unknown custom block parameter '%s'."; 
    public const MSG_REASSIGNMENT_CUSTOM_BLOCK_PARAMETER = "The custom block parameter '%s' is already assigned."; 

    public const MSG_CLOSING_BLOCK_NOW_ALLOWED = "This block cannot be closed.";

    public const MSG_NO_SOURCE_CODE = "No source code found in the source object, cannot parse it.";

    // Custom block specific error messages
    public const MSG_EXPECT_REQUIRED_OR_OPTIONAL_PARAMETER_DEFINITION_IN_CUSTOM_BLOCK = "The custom block definition specifies the startExpressionName '%s' but this name could not be found in either the optionalParameters or the requiredParameters array.";

    public const MSG_EXPECT_CLOSING_BLOCK_COMMENT       = "Could not find the closing block comment '*}' that belongs to this opening block comment.";
    public const MSG_EXPECT_CLOSING_MULTILINE_COMMENT   = "Could not find the closing comment '*/' that belongs to this opening comment.";

    public const MSG_CACHE_BLOCK_IN_DYNAMIC_BLOCK       = "Cache block cannot be used inside a dynamic block.";
    public const MSG_CACHE_BLOCK_IN_CACHE_BLOCK       = "Cache block cannot be used inside another cache block or inside cache_template.";

    public const MSG_NAMED_PARAMETER_ALREADY_ASSIGNED   = "Named parameter: '%s' is already assigned.";



    // Inconsistencies with eZ publish 3.
    public const LNG_INVALID_NAMESPACE_MARKER          = "The namespace marker (:) was used in template engine in eZ publish 3.x but is no longer allowed.";
    public const LNG_INVALID_NAMESPACE_ROOT_MARKER = "The namespace-root marker (#) was used in the template engine of eZ publish 3.x but it's no longer allowed.";




    // Runtime errors
    public const RT_IMPORT_VALUE_MISSING               = "The external (use) variable '%s' is not set in template: %s and called from %s" ; 
    
    


}


?>
