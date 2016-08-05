<?php

namespace Rankster\Service;

use Rankster\Entity\Session;
use Rankster\Entity\User;

class AuthSession
{

    private static $token = 'token';
    private static $justLoggedIn = 'just_logged';
    private static $userId;
    /** @var bool|User */
    private static $user;

    public static function justLoggedIn()
    {
        if (isset($_COOKIE[self::$justLoggedIn])) {
            setcookie(self::$justLoggedIn, '', time() - 3600, '/');
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return bool|int
     */
    public static function getUserId()
    {
        if (null === self::$userId) {
            if (isset($_COOKIE[self::$token])) {
                $token = $_COOKIE[self::$token];
                $session = Session::findByPrimaryKey($token);

                if (!$session) {
                    setcookie(self::$token, "", time() - 3600, '/');
                    self::$userId = false;
                } else {
                    self::$userId = $session->userId;
                }
            } else {
                self::$userId = false;
            }
        }
        return self::$userId;
    }


    /**
     * @return bool|User
     */
    public static function getUser()
    {
        if (null === self::$user) {
            if (!$userId = self::getUserId()) {
                self::$user = false;
            } else {
                self::$user = User::findByPrimaryKey($userId);
            }
        }
        return self::$user;
    }


    public static function clear()
    {
        if (isset($_COOKIE[self::$token])) {
            Session::statement()->delete()->where('? = ?', Session::columns()->token, $_COOKIE[self::$token])
                ->query()->execute();
            setcookie(self::$token, "", time() - 3600, '/');
        }
    }

    public static function makeRandomToken()
    {
        if (function_exists('random_bytes')) {
            $token = random_bytes(16);
        } else {
            $token = md5(rand() . microtime(1) . $_SERVER['REMOTE_ADDR'], true);
        }
        $token = substr(base64_encode($token), 0, 16);
        return $token;
    }


    public static function set($userId)
    {
        if (isset($_COOKIE[self::$token])) {
            $session = Session::findByPrimaryKey($_COOKIE[self::$token]);
            if ($session) {
                if ($session->userId === $userId) {
                    return;
                }
            } else {
                $session = new Session();
            }
        } else {
            do {
                $token = self::makeRandomToken();
            } while (Session::findByPrimaryKey($token));
            $session = new Session();
            setcookie(self::$token, $token, time() + 90 * 86400, '/');
            setcookie(self::$justLoggedIn, 1, null, '/');
            $session->token = $token;
        }

        $session->userId = $userId;
        $session->save();
    }
}