<?php
/**
 * @package Workflow
 * @subpackage Tests
 * @version 1.4.1
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * @package Workflow
 * @subpackage Tests
 */
class ezcWorkflowTestVariableHandler implements ezcWorkflowVariableHandler
{
    protected $storage = ['foo' => 'bar'];

    public function load( ezcWorkflowExecution $execution, $variableName )
    {
        return $this->storage[$variableName];
    }

    public function save( ezcWorkflowExecution $execution, $variableName, $value )
    {
        $this->storage[$variableName] = $value;
    }
}
?>
