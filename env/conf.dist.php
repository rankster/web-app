<?php

namespace Rankster;

use Rankster\Service\FacebookLogin;
use Rankster\Service\FacebookSettings;
use Yaoi\Database;

Database::register('mysqli://root:password@localhost/rankster');
FacebookLogin::register(function(){
    $settings = new FacebookSettings();
    $settings->appId = 'app-id';
    $settings->appSecret = 'app-secret';
    return $settings;
});
