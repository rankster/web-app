<?php

namespace Rankster\Api\V1;

use Rankster\Service\Facebook;
use Yaoi\Command;
use Yaoi\Command\Definition;

class Login extends Command
{
    /**
     * Required setup option types in provided options object
     * @param $definition Definition
     * @param $options static|\stdClass
     */
    static function setUpDefinition(Definition $definition, $options)
    {
        
    }

    public function performAction()
    {
        session_start();
        $fbLogin = new Facebook\Login();
        try {
            $accessToken = $fbLogin->getStoredAccessToken();
            if (!$accessToken) {
                $accessToken = $fbLogin->callback();
            }
            $user = new Facebook\User($accessToken);
            $data = $user->getData();
            var_dump($data);
        } catch (\Exception $e) {
            return ['ok' => false, 'error' => $e->getMessage()];
        }

        return ['ok' => true];
    }
}
