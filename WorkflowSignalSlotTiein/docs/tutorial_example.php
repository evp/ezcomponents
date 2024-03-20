<?php
// Connect signals to slots.
$signals  = new ezcSignalCollection;
$receiver = new MyReceiver;

$signals->connect( 'afterExecutionStarted', [$receiver, 'afterExecutionStarted'] );
$signals->connect( 'afterExecutionSuspended', [$receiver, 'afterExecutionSuspended'] );
$signals->connect( 'afterExecutionResumed', [$receiver, 'afterExecutionResumed'] );
$signals->connect( 'afterExecutionCancelled', [$receiver, 'afterExecutionCancelled'] );
$signals->connect( 'afterExecutionEnded', [$receiver, 'afterExecutionEnded'] );
$signals->connect( 'beforeNodeActivated', [$receiver, 'beforeNodeActivated'] );
$signals->connect( 'afterNodeActivated', [$receiver, 'afterNodeActivated'] );
$signals->connect( 'afterNodeExecuted', [$receiver, 'afterNodeExecuted'] );
$signals->connect( 'afterRolledBackServiceObject', [$receiver, 'afterRolledBackServiceObject'] );
$signals->connect( 'afterThreadStarted', [$receiver, 'afterThreadStarted'] );
$signals->connect( 'afterThreadEnded', [$receiver, 'afterThreadEnded'] );
$signals->connect( 'beforeVariableSet', [$receiver, 'beforeVariableSet'] );
$signals->connect( 'afterVariableSet', [$receiver, 'afterVariableSet'] );
$signals->connect( 'beforeVariableUnset', [$receiver, 'beforeVariableUnset'] );
$signals->connect( 'afterVariableUnset', [$receiver, 'afterVariableUnset'] );

// Set up database connection.
$db = ezcDbFactory::create( 'mysql://test@localhost/test' );

// Set up workflow definition storage (database).
$definition = new ezcWorkflowDatabaseDefinitionStorage( $db );

// Load latest version of workflow named "Test".
$workflow = $definition->loadByName( 'Test' );

// Set up database-based workflow executer.
$execution = new ezcWorkflowDatabaseExecution( $db );

// Pass workflow object to workflow executer.
$execution->workflow = $workflow;

// Register SignalSlot workflow engine plugin.
$plugin = new ezcWorkflowSignalSlotPlugin;
$plugin->signals = $signals;

$execution->addPlugin( $plugin );

// Start workflow execution.
$id = $execution->start();
?>
