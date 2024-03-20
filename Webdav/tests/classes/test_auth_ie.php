<?php

require_once 'test_auth.php';

class ezcWebdavTestAuthIe extends ezcWebdavTestAuth
{
    public function __construct()
    {
        $this->permissions = ['' => ['some' => ezcWebdavAuthorizer::ACCESS_WRITE], 'collection' => ['some' => ezcWebdavAuthorizer::ACCESS_WRITE]];
        $this->credentials = ['foo'    => 'bar', 'some'   => 'thing', '23'     => '42', 'Mufasa' => 'Circle Of Life'];
    }
}

?>
