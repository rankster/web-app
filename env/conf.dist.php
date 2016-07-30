<?php

namespace Rankster;

use Rankster\Service\Facebook;
use Rankster\Service\Facebook\Settings;
use Yaoi\Database;

Database::register('mysqli://root:password@localhost/rankster');
Facebook::register(function(){
    $settings = new Settings();
    $settings->appId = 'app-id';
    $settings->appSecret = 'app-secret';
    return $settings;
});
