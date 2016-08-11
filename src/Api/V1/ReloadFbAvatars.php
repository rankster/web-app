<?php

namespace Rankster\Api\V1;

use Rankster\Entity\User;
use Yaoi\Command;
use Yaoi\Command\Definition;

class ReloadFbAvatars extends Command
{
    static function setUpDefinition(Definition $definition, $options)
    {
    }

    public function performAction()
    {
        $users = User::statement()->query()->fetchAll();

        /** @var User $user */
        foreach ($users as $user) {
            $user->downloadImage('https://graph.facebook.com/' . $user->facebookId . '/picture?width=200&height=200');
        }
    }


}