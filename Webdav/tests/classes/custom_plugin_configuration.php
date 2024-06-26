<?php
/**
 * File containing the fooCustomWebdavPluginConfiguration class.
 *
 * @package Webdav
 * @subpackage Tests
 * @version 1.1.4
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Mock class to remove "abstract".
 * 
 * @package Webdav
 * @subpackage Tests
 * @version 1.1.4
 */
class fooCustomWebdavPluginConfiguration extends ezcWebdavPluginConfiguration
{
    public $foo;

    public $callbackCalled = 0;

    public $namespace = 'foonamespace';

    public $hooks;

    public $init = false;

    public function getHooks()
    {
        return ( $this->hooks ?? ['ezcWebdavTransport' => ['beforeParseRequest' => [['ezcWebdavPluginRegistryTest', 'callbackBeforeTest'], [$this, 'testCallback']], 'afterProcessResponse' => [['ezcWebdavPluginRegistryTest', 'callbackAfterTest'], [$this, 'testCallback']]]] );
    }

    public function testCallback()
    {
        ++$this->callbackCalled;
    }


    public function getNamespace()
    {
        return $this->namespace;
    }

    public function init()
    {
        $this->init = true;
    }
}



?>
