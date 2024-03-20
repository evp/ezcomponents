<?php
require_once 'tutorial_autoload.php';

$credentials1 = new ezcAuthenticationPasswordCredentials( 'jan.modaal', 'b1b3773a05c0ed0176787a4f1574ff0075f7521e' ); // incorrect password
$credentials2 = new ezcAuthenticationPasswordCredentials( 'john.doe', 'wpeE20wyWHnLE' ); // correct username + password

$options = new ezcAuthenticationGroupOptions();
$options->multipleCredentials = true;
$options->mode = ezcAuthenticationGroupFilter::MODE_AND;
$group = new ezcAuthenticationGroupFilter( [], $options );

$group->addFilter( new ezcAuthenticationHtpasswdFilter( '../../tests/filters/htpasswd/data/htpasswd' ), $credentials1 );
$group->addFilter( new ezcAuthenticationHtpasswdFilter( '../../tests/filters/htpasswd/data/htpasswd' ), $credentials2 );

$authentication = new ezcAuthentication( $credentials1 );
$authentication->addFilter( $group );
// add more filters if needed

if ( !$authentication->run() )
{
    // authentication did not succeed, so inform the user
    $status = $authentication->getStatus();

    $err = [['ezcAuthenticationHtpasswdFilter' => [ezcAuthenticationHtpasswdFilter::STATUS_OK => '', ezcAuthenticationHtpasswdFilter::STATUS_USERNAME_INCORRECT => 'Incorrect username ' . $credentials1->id, ezcAuthenticationHtpasswdFilter::STATUS_PASSWORD_INCORRECT => 'Incorrect password for ' . $credentials1->id]], ['ezcAuthenticationHtpasswdFilter' => [ezcAuthenticationHtpasswdFilter::STATUS_OK => '', ezcAuthenticationHtpasswdFilter::STATUS_USERNAME_INCORRECT => 'Incorrect username ' . $credentials2->id, ezcAuthenticationHtpasswdFilter::STATUS_PASSWORD_INCORRECT => 'Incorrect password for ' . $credentials2->id]]];

    foreach ( $status as $line => $error )
    {
        $key = key($error);
        $value = current($error);
        next($error);
        echo $err[$line][$key][$value] . "\n";
    }
}
else
{
    // authentication succeeded, so allow the user to see his content
}
?>
