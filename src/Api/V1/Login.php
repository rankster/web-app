<?php

namespace Rankster\Api\V1;

use Rankster\Entity\User;
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
            $data = $user->getData('me', ['picture', 'name', 'email']);
            $data->getPicture()->getUrl();

            $userEntity = new User();
            $userEntity->facebookId = $data->getId();
            if (!$userEntity->findSaved()) {
                $userEntity->name = $data->getName();
                if ($data->getPicture() && $data->getPicture()->getUrl()) {
                    $userEntity->downloadImage($data->getPicture()->getUrl());
                }
                $userEntity->email = $data->getEmail();
                $userEntity->findOrSave();
            }

            $_SESSION['user_id'] = $userEntity->id;
            $_SESSION['user_facebook_id'] = $userEntity->facebookId;
            $_SESSION['user_name'] = $userEntity->name;
            $_SESSION['picture'] = $userEntity->picturePath;
            header('Location: /');
            exit;
        } catch (\Exception $e) {
            return ['ok' => false, 'error' => $e->getMessage()];
        }

        return ['ok' => true];
    }
}
