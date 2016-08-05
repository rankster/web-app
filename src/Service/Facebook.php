<?php

namespace Rankster\Service;

use Rankster\Service\Facebook\Settings;
use Yaoi\Service;

/**
 * @method Settings getSettings()
 */
class Facebook extends Service
{
    protected $sdkInstance;
    /** @var Settings */
    protected $settings;

    public function getSDK()
    {
        if (!$this->sdkInstance) {
            $this->sdkInstance = new \Facebook\Facebook([
                'app_id'                    => $this->getSettings()->appId,
                'app_secret'                => $this->getSettings()->appSecret,
                'persistent_data_handler'   => 'session',
                //'default_graph_version' => $this->getSettings()->defaultGraphVersion,
            ]);
        }

        return $this->sdkInstance;
    }

    protected static function getSettingsClassName()
    {
        return Settings::className();
    }

    public function getCallbackUri()
    {
        return $this->getSettings()->callbackUri;
    }
}
