<?php

namespace Rankster\Api\V1\Oauth;

use Rankster\Entity\User;
use Rankster\Service\AuthSession;
use Yaoi\Command;
use Yaoi\Command\Definition;

class Google extends Command
{
    public $state;
    public $code;

    /**
     * @param Definition $definition
     * @param \stdClass|static $options
     */
    static function setUpDefinition(Definition $definition, $options)
    {
        $options->state = Command\Option::create()->setType()
            ->setDescription('State information (contains initial url)');
        $options->code = Command\Option::create()->setType()->setIsRequired()
            ->setDescription('Auth security code');
    }

    public function performAction()
    {
        $google = \Rankster\Service\Google::getInstance();
        $token = $google->getToken($this->code);
        $info = $google->getUserInfo();

        if (empty($info['id'])) {
            throw new \Exception('Bad response');
        }

        $user = new User();
        $user->email = $info['email'];
        if ($saved = $user->findSaved()) {
            $user = $saved;
            if (empty($user->googleId)) {
                $user->googleId = $info['id'];
                $user->save();
            }
        } else {
            $user->name = $info['name'];
            $user->googleId = $info['id'];
            if (!empty($info['picture'])) {
                $user->downloadImage($info['picture'], $user->googleId);
            }
            $user->save();
        }
        AuthSession::set($user->id);
        header("Location: $this->state");
        exit();
    }


}