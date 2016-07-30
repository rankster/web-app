<?php

namespace Rankster\Web\Match;


use Yaoi\Command;
use Yaoi\Command\Definition;

class Match extends Command
{
    /**
     * @var Command
     */
    public $action;

    /**
     * @param Definition $definition
     * @param \stdClass|static $options
     */
    static function setUpDefinition(Definition $definition, $options)
    {
        $options->action = Command\Option::create()
            ->addToEnum(Create::definition())
            ->addToEnum(Details::definition())
            ->setIsUnnamed()
            ->setIsRequired();

    }


    public function performAction()
    {
        $this->action->performAction();
    }

}