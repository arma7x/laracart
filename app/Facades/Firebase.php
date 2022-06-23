<?php
namespace App\Facades;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Contract;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;
use Illuminate\Support\Facades\App;

class Firebase
{
    private Factory $instance;
    protected String $sessionCookieName = 'firebase_token';
    protected int $sessionCookieExpire = 1209600;
    private $_authInstance = null;
    private $_token = false;

    public function __construct(string $serviceAccountJSON, String $sessionCookieName, int $sessionCookieExpire)
    {
        $this->instance = (new Factory)->withServiceAccount($serviceAccountJSON);
        $this->sessionCookieName = $sessionCookieName;
        $this->sessionCookieExpire = $sessionCookieExpire;
    }

    public function instance(): Factory
    {
        return $this->instance;
    }

    public function auth(): Contract\Auth
    {
        if ($this->_authInstance == null)
            $this->_authInstance = $this->instance->createAuth();
        return $this->_authInstance;
    }

    public function user()
    {
        if ($this->_token === false)
        {
            try {
                $this->_token = $this->verifySessionCookie($this->getSessionCookie());
            } catch (\Exception $e) {
                $this->_token = null;
                return $this->_token;
            }
        } else if ($this->_token === null) {
            return $this->_token;
        }
        return $this->_token->claims()->all();
    }

    public function getSessionCookie()
    {
        return isset($_COOKIE[$this->sessionCookieName]) ? $_COOKIE[$this->sessionCookieName] : null;
    }

    public function verifySessionCookie($cookie)
    {
        if ($cookie === null)
            throw new \Exception(__('Unauthorized'));
        $authInstance = $this->_authInstance ?: $this->auth();
        $this->_token = $authInstance->verifySessionCookie($cookie, TRUE);
        return $this->_token;
    }

    public function createSessionCookie(String $token)
    {
        $authInstance = $this->_authInstance ?: $this->auth();
        $verifiedIdToken = $authInstance->verifyIdToken($token, TRUE);
        $firebaseToken = $authInstance->createSessionCookie($token, $this->sessionCookieExpire);
        setcookie($this->sessionCookieName, $firebaseToken, time() + $this->sessionCookieExpire, '/', '', App::environment() === 'production', true);
    }

    public function destroySessionCookie()
    {
        setcookie($this->sessionCookieName, null, -1, '/', '', App::environment() === 'production', true);
    }

    public function verifySessionToken($token)
    {
        if ($token === null)
            throw new \Exception(__('Unauthorized'));
        $authInstance = $this->_authInstance ?: $this->auth();
        $this->_token = $authInstance->verifyIdToken($token, TRUE);
        return $this->_token;
    }
}
