<?php
$definition = ['firstName' => new ezcInputFormDefinitionElement(
    ezcInputFormDefinitionElement::REQUIRED, 'string'
), 'lastName' => new ezcInputFormDefinitionElement(
    ezcInputFormDefinitionElement::REQUIRED, 'string'
), 'age' => new ezcInputFormDefinitionElement(
    ezcInputFormDefinitionElement::REQUIRED, 'int',
    ['min_range' => 1, 'max_range' => 99],
    FILTER_FLAG_ALLOW_HEX
), 'email' => new ezcInputFormDefinitionElement(
    ezcInputFormDefinitionElement::REQUIRED, 'validate_email'
)];
?>
