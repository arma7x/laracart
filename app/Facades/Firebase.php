<?php
namespace App\Facades;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Contract;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;

class Firebase
{
    private Factory $instance;
    protected String $sessionTokenName = 'firebase_token';
    protected int $sessionTokenExpire = 1209600;
    private $_authInstance = NULL;
    private $_user = NULL;

    public function __construct(string $serviceAccountJSON, String $sessionTokenName, int $sessionTokenExpire)
    {
        $this->instance = (new Factory)->withServiceAccount($serviceAccountJSON);
        $this->sessionTokenName = $sessionTokenName;
        $this->sessionTokenExpire = $sessionTokenExpire;
    }

    public function instance(): Factory
    {
        return $this->instance;
    }

    public function auth(): Contract\Auth
    {
        if ($this->_authInstance == NULL)
            $this->_authInstance = $this->instance->createAuth();
        return $this->_authInstance;
    }

    public function user()
    {
        if ($this->_user == NULL)
        {
            try {
                $this->_user = $this->verifySessionCookie($this->getSessionCookie());
            } catch (\Exception $e) {
                $this->_user = NULL;
            }
        }
        return $this->_user;
    }

    public function getSessionCookie()
    {
        return request()->cookie($this->sessionTokenName);
    }

    public function verifySessionCookie($cookie)
    {
        if ($cookie == NULL)
            throw new \Exception(__('Unauthorized'));
        $firebaseAuth = $this->_authInstance ?: $this->auth();
        $verifiedToken = $firebaseAuth->verifySessionCookie($cookie, TRUE);
        $this->_user = $verifiedToken->claims()->all();
        return $this->_user;
    }

    public function createSessionCookie(String $token)
    {
        $firebaseAuth = $this->_authInstance ?: $this->auth();
        $verifiedIdToken = $firebaseAuth->verifyIdToken($token, TRUE);
        $firebaseToken = $firebaseAuth->createSessionCookie($token, $this->sessionTokenExpire);
        setcookie($this->sessionTokenName, $firebaseToken, time() + $this->sessionTokenExpire, '/', '', false, true);
    }

    public function destroySessionCookie()
    {
        setcookie($this->sessionTokenName, null, -1, '/', '', false, true);
    }
}
