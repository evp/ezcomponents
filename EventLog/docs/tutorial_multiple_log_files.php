<?php
require_once 'tutorial_autoload.php';
date_default_timezone_set( "UTC" );

// Same set up as the previous examples.
$log = ezcLog::getInstance();

// Create the writers
$writeAll = new ezcLogUnixFileWriter( "/tmp/logs", "general.log" );
$writePayment = new ezcLogUnixFileWriter( "/tmp/logs", "payment.log" );
$writeAuditTrails = new ezcLogUnixFileWriter( "/tmp/logs", "audit_trails.log" );

$debugFilter = new ezcLogFilter;
$debugFilter->severity = ezcLog::DEBUG;
$log->getmapper()->appendRule( new ezcLogFilterRule( $debugFilter, [], false ) );

$auditFilter = new ezcLogFilter;
$auditFilter->severity = ezcLog::SUCCESS_AUDIT | ezcLog::FAILED_AUDIT;
$log->getMapper()->appendRule( new ezcLogFilterRule( $auditFilter, $writeAuditTrails, true ) );

$paymentFilter = new ezcLogFilter;
$paymentFilter->source = ["Payment"];
$log->getMapper()->appendRule( new ezcLogFilterRule( $paymentFilter, $writePayment, true ) );

$log->getMapper()->appendRule( new ezcLogFilterRule( new ezcLogFilter, $writeAll, true ) );


// Writing some log messages.
$log->log( "Authentication failed", ezcLog::FAILED_AUDIT, ["source" => "security", "category" => "login/logoff"] );

$log->source = "Payment"; 
$log->log( "Connecting with the server.", ezcLog::DEBUG, ["category" => "external connections"] );

$log->log( "Paid with credit card.", ezcLog::SUCCESS_AUDIT, ["category" => "shop"] );

$log->log( "The credit card information is removed.", ezcLog::NOTICE, ["category" => "shop"] );
?>
