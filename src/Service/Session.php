<?php

namespace Rankster\Service;

class Session
{
    private static $isStarted = false;
    private static $hasCookie;

    public static function start()
    {
        if (!self::$isStarted) {
            self::$isStarted = true;
            session_start();
        }
    }

    public static function startIfExists()
    {
        if (!self::$isStarted) {
            if (null === self::$hasCookie) {
                self::$hasCookie = !empty($_COOKIE[ini_get('session.name')]);
            }

            if (!self::$hasCookie) {
                return;
            }

            self::$isStarted = true;
            session_start();
        }
    }


    public static function set($key, $value)
    {
        self::start();
        $_SESSION[$key] = $value;
    }


    public static function get($key)
    {
        self::startIfExists();
        if (array_key_exists($key, $_SESSION)) {
            $result = $_SESSION[$key];
        } else {
            $result = null;
        }

        return $result;
    }

    public static function getAndRemove($key)
    {
        self::startIfExists();
        if (array_key_exists($key, $_SESSION)) {
            $result = $_SESSION[$key];
            unset($_SESSION[$key]);
        } else {
            $result = null;
        }

        return $result;
    }


    public static function destroyEmpty()
    {
        if (self::$isStarted && empty($_SESSION)) {
            session_destroy();
            setcookie(ini_get('session.name'), null, time() - 86400, '/');
        }
    }


    public static function addSuccessMessage($message)
    {
        self::start();

        if (!isset($_SESSION['messages']['success'])) {
            $_SESSION['messages']['success'] = [];
        }
        $_SESSION['messages']['success'][] = $message;
    }

    public static function getSuccessMessages($wipe = true)
    {
        self::startIfExists();

        if (!isset($_SESSION['messages']['success'])) {
            return [];
        }

        $messages = $_SESSION['messages']['success'];
        if ($wipe) {
            $_SESSION['messages']['success'] = [];
        }

        return $messages;
    }

}