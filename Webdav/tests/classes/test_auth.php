<?php

class ezcWebdavTestAuth extends ezcWebdavDigestAuthenticatorBase implements ezcWebdavAuthorizer, ezcWebdavLockAuthorizer
{
    public $tokenAssignement = [];

    public $permissions = ['a' => ['foo'  => ezcWebdavAuthorizer::ACCESS_READ, 'some' => ezcWebdavAuthorizer::ACCESS_WRITE], 'b' => ['foo'  => ezcWebdavAuthorizer::ACCESS_WRITE, 'some' => ezcWebdavAuthorizer::ACCESS_WRITE, ''     => ezcWebdavAuthorizer::ACCESS_WRITE], 'c' => ['foo'  => ezcWebdavAuthorizer::ACCESS_READ, 'some' => ezcWebdavAuthorizer::ACCESS_READ, ''     => ezcWebdavAuthorizer::ACCESS_READ], '' => [''     => ezcWebdavAuthorizer::ACCESS_WRITE, 'some' => ezcWebdavAuthorizer::ACCESS_WRITE], 'collection' => [''     => ezcWebdavAuthorizer::ACCESS_WRITE, 'some' => ezcWebdavAuthorizer::ACCESS_WRITE], 'secure_collection' => ['some' => ezcWebdavAuthorizer::ACCESS_WRITE]];

    public $credentials = ['foo'    => 'bar', 'some'   => 'thing', '23'     => '42', 'Mufasa' => 'Circle Of Life'];

    public function authenticateAnonymous( ezcWebdavAnonymousAuth $data )
    {
        return true;
    }

    public function authenticateBasic( ezcWebdavBasicAuth $data )
    {
        return ( isset( $this->credentials[$data->username] ) && $this->credentials[$data->username] === $data->password );
    }

    public function authenticateDigest( ezcWebdavDigestAuth $data )
    {
        return ( isset( $this->credentials[$data->username] ) && $this->checkDigest( $data, $this->credentials[$data->username] ) );
    }

    public function authorize( $user, $path, $access = ezcWebdavAuthorizer::ACCESS_READ )
    {
        preg_match( '(^/([^/]+)(/|$))', $path, $matches );
        $basedir = ( $matches[1] ?? '' );
        return ( !isset( $this->permissions[$basedir] )
            || ( isset( $this->permissions[$basedir][$user] ) 
                && $this->permissions[$basedir][$user] >= $access )
            );
    }

    public function assignLock( $user, $lockToken )
    {
        if ( !isset( $this->tokenAssignement[$user] ) )
        {
            $this->tokenAssignement[$user] = [];
        }
        $this->tokenAssignement[$user][$lockToken] = true;
    }

    public function ownsLock( $user, $lockToken )
    {
        return (
            isset( $this->tokenAssignement[$user] )
            && isset( $this->tokenAssignement[$user][$lockToken] )
        );
    }

    public function releaseLock( $user, $lockToken )
    {
        unset( $this->tokenAssignement[$user][$lockToken] );
    }
}

?>
