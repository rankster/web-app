<?php

namespace Rankster;

use Rankster\Service\Facebook;
use Rankster\Service\Google;
use Yaoi\Database;
use Yaoi\Log;

if (isset($_COOKIE['tz'])) {
    date_default_timezone_set($_COOKIE['tz']);
}

Database::register(function () {
    $config = new Database\Settings('mysqli://root:password@localhost/rankster');
    if (isset($_COOKIE['tz'])) {
        $config->timezone = $_COOKIE['tz'];
    }
    $db = new Database($config);
    $db->log(new Log('stdout'));
    return $db;
});

Facebook::register(function () {
    $settings = new Facebook\Settings();
    $settings->appId = 'aaa';
    $settings->appSecret = 'aaa';
    return $settings;
});


Google::register(function () {
    $settings = new Google\GoogleSettings();
    $settings->clientId = 'aaa';
    $settings->secret = 'aaa';
    $settings->redirectUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/v1/oauth/google';
    return $settings;
});
