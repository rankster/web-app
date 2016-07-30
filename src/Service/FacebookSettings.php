<?php

namespace Rankster\Service;


use Yaoi\Service\Settings;

class FacebookSettings extends Settings
{
    public $appId;
    public $appSecret;
    public $defaultGraphVersion = '2.2';
    public $callbackUri = 'http://rankster.penix.tk/v1/login';



}
