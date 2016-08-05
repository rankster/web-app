<?php

namespace Rankster\Web;

use Rankster\Service\AuthSession;
use Rankster\Service\Facebook\Login as FacebookLogin;
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
        if (AuthSession::getUserId()) {
            header('Location: /');
            exit();
        }
        session_start();
        $fbLogin = new FacebookLogin();
        $url = $fbLogin->getLoginUrl();
        header('Location: ' . $url);
        exit();
    }
}
