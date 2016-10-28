<?php

namespace Rankster\Api\Admin;


use Rankster\Entity\User;
use Yaoi\Command;
use Yaoi\Command\Definition;

class AddUser extends Command
{
    public $email;
    public $name;
    public $login;
    public $picturePath;


    /**
     * @param Definition $definition
     * @param \stdClass|static $options
     */
    static function setUpDefinition(Definition $definition, $options)
    {
        $options->email = Command\Option::create()->setType()->setIsRequired()->setDescription('User email');
        $options->name = Command\Option::create()->setType()->setIsRequired();
        $options->login = Command\Option::create()->setType()->setIsRequired();
        $options->picturePath = Command\Option::create()->setType()->setIsRequired();
    }

    public function performAction()
    {
        $user = new User();
        $user->email = $this->email;
        $user->name = $this->name;
        $user->login = $this->login;
        $user->picturePath = $this->picturePath;
        $user->findOrSave();

        return $user->toArray();
    }


}