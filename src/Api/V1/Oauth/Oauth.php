<?php

namespace Rankster\Api\V1\Oauth;


use Yaoi\Command;

class Oauth extends Command
{
    /**
     * @var Command
     */
    public $action;

    /**
     * @param Command\Definition $definition
     * @param \stdClass|static $options
     */
    static function setUpDefinition(Command\Definition $definition, $options)
    {
        $options->action = Command\Option::create()
            ->addToEnum(Google::definition())
            ->setIsUnnamed()
            ->setIsRequired();
    }


    public function performAction()
    {
        $this->action->performAction();
    }

}