<?php

namespace Rankster\Web\User;
use Yaoi\Command;

class User extends Command
{
    /**
     * @var Command
     */
    public $action;

    /**
     * @param Definition $definition
     * @param \stdClass|static $options
     */
    static function setUpDefinition(Command\Definition $definition, $options)
    {
        $options->action = Command\Option::create()
            ->addToEnum(Details::definition())
            ->setIsUnnamed()
            ->setIsRequired();

    }


    public function performAction()
    {
        $this->action->performAction();
    }

}