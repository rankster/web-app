<?php

namespace Rankster\Service\Facebook;

use Yaoi\Service\Settings as YSettings;

class Settings extends YSettings
{
    public $appId;
    public $appSecret;
    public $defaultGraphVersion = '2.2';
    public $callbackUri;

    public function __construct($dsnUrl)
    {
        parent::__construct($dsnUrl);
        if (null === $this->callbackUri) {
            $this->callbackUri = 'http://' . str_replace('www.', '', $_SERVER['HTTP_HOST']) . '/v1/login';
        }
    }
}
