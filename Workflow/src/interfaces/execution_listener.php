<?php
/**
 * File containing the ezcWorkflowExecutionListener interface.
 *
 * @package Workflow
 * @version 1.4.1
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Interface for workflow execution listeners.
 *
 * @package Workflow
 * @version 1.4.1
 */
interface ezcWorkflowExecutionListener
{
    /**
     * Debug severity constant.
     */
     public const DEBUG          = 1;

    /**
     * Success audit severity constant.
     */
     public const SUCCESS_AUDIT  = 2;

    /**
     * Failed audit severity constant.
     */
     public const FAILED_AUDIT   = 4;

     /**
      * Info severity constant.
      */
     public const INFO           = 8;

     /**
      * Notice severity constant.
      */
     public const NOTICE         = 16;

     /**
      * Warning severity constant.
      */
     public const WARNING        = 32;

     /**
      * Error severity constant.
      */
     public const ERROR          = 64;

     /**
      * Fatal severity constant.
      */
     public const FATAL          = 128;

    /**
     * Called to inform about events.
     *
     * @param string  $message
     * @param int $type
     */
    public function notify( $message, $type = ezcWorkflowExecutionListener::INFO );
}
?>
