<?php

namespace Rankster\Service;

use Yaoi\Service;

/**
 * @method FacebookSettings getSettings()
 */
class FacebookLogin extends Service
{
    /** @var FacebookSettings */
    protected $settings;

    public function callback()
    {
        $this->getSettings()->appId;
    }

    protected static function getSettingsClassName()
    {
        return FacebookSettings::className();
    }


}
