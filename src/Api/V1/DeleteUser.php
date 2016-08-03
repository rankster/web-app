<?php

namespace Rankster\Api\V1;


use Rankster\Api\ClientException;
use Rankster\Entity\User;
use Rankster\Manager\UserManager;
use Yaoi\Command;
use Yaoi\Command\Definition;

class DeleteUser extends Command
{
    public $userId;

    /**
     * @param Definition $definition
     * @param \stdClass|static $options
     */
    static function setUpDefinition(Definition $definition, $options)
    {
        $options->userId = Command\Option::create()->setType()->setIsRequired();
    }

    public function performAction()
    {
        $user = User::findByPrimaryKey($this->userId);
        if (!$user) {
            throw new ClientException('User not found');
        }
        UserManager::deleteUser($user);
        return $user;
    }


}