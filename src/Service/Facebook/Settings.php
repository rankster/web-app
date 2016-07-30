<?php

namespace Rankster\Service\Facebook;

use Yaoi\Service\Settings as YSettings;

class Settings extends YSettings
{
    public $appId;
    public $appSecret;
    public $defaultGraphVersion = '2.2';
    public $callbackUri = 'http://rankster.penix.tk/v1/login';
}
