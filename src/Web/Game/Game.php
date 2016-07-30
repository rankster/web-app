<?php

namespace Rankster\Web\Game;


use Yaoi\Command;
use Yaoi\Command\Definition;

class Game extends Command
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
            ->addToEnum(Details::definition())
            ->addToEnum(Items::definition())
            ->setIsUnnamed()
            ->setIsRequired();

    }


    public function performAction()
    {
        $this->action->performAction();
    }

}