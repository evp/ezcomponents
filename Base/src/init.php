<?php
/**
 * File containing the ezcBaseInit class.
 *
 * @package Base
 * @version 1.8
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */
/**
 * Provides a method to implement delayed initialization of objects.
 *
 * With the methods in this class you can implement callbacks to configure
 * singleton classes. In order to do so you will have to change the
 * getInstance() method of your singleton class to include a call to
 * ezcBaseInit::fetchConfig() as in the following example:
 *
 * <code>
 * <?php
 * public static function getInstance()
 * {
 *     if ( is_null( self::$instance ) )
 *     {
 *         self::$instance = new ezcConfigurationmanager();
 *         ezcBaseInit::fetchConfig( 'ezcInitConfigurationManager', self::$instance );
 *     }
 *     return self::$instance;
 * }
 * ?>
 * </code>
 *
 * You will also need to configure which callback class to call. This you do
 * with the ezcBaseInit::setCallback() method. The following examples sets the
 * callback classname for the configuration identifier
 * 'ezcInitConfigurationManager' to 'cfgConfigurationManager':
 *
 * <code>
 * <?php
 * ezcBaseInit::setCallback( 'ezcInitConfigurationManager', 'cfgConfigurationManager' );
 * ?>
 * </code>
 *
 * The class 'cfgConfigurationManager' is required to implement the
 * ezcBaseConfigurationInitializer interface, which defines only one method:
 * configureObject(). An example on how to implement such a class could be:
 *
 * <code>
 * <?php
 * class cfgConfigurationManager implements ezcBaseConfigurationInitializer
 * {
 *     static public function configureObject( ezcConfigurationManager $cfgManagerObject )
 *     {
 *         $cfgManagerObject->init( 'ezcConfigurationIniReader', 'settings', array( 'useComments' => true ) );
 *     }
 * }
 * ?>
 * </code>
 *
 * Of course the implementation of this callback class is up to the application
 * developer that uses the component (in this example the Configuration
 * component's class ezcConfigurationManager).
 *
 * @package Base
 * @version 1.8
 */
class ezcBaseInit
{
    /**
     * Contains the callback where the identifier is the key of the array, and the classname to callback to the value.
     *
     * @var array(string=>string)
     */
    static private $callbackMap = [];

    /**
     * Adds the classname $callbackClassname as callback for the identifier $identifier.
     *
     * @param string $identifier
     * @param string $callbackClassname
     */
    public static function setCallback( $identifier, $callbackClassname )
    {
        if ( array_key_exists( $identifier, self::$callbackMap ) )
        {
            throw new ezcBaseInitCallbackConfiguredException( $identifier, self::$callbackMap[$identifier] );
        }
        else
        {
            // Check if the passed classname actually exists
            if ( !ezcBaseFeatures::classExists( $callbackClassname, true ) )
            {
                throw new ezcBaseInitInvalidCallbackClassException( $callbackClassname );
            }

            // Check if the passed classname actually implements the interface.
            if ( !in_array( 'ezcBaseConfigurationInitializer', class_implements( $callbackClassname ) ) )
            {
                throw new ezcBaseInitInvalidCallbackClassException( $callbackClassname );
            }

            self::$callbackMap[$identifier] = $callbackClassname;
        }
    }

    /**
     * Uses the configured callback belonging to $identifier to configure the $object.
     *
     * The method will return the return value of the callback method, or null
     * in case there was no callback set for the specified $identifier.
     *
     * @param string $identifier
     * @param object $object
     * @return mixed
     */
    public static function fetchConfig( $identifier, $object )
    {
        if ( isset( self::$callbackMap[$identifier] ) )
        {
            $callbackClassname = self::$callbackMap[$identifier];
            return call_user_func( [$callbackClassname, 'configureObject'], $object );
        }
        return null;
    }
}
?>
