<?php

namespace Rankster\Api\V1;


use Yaoi\Command;
use Yaoi\Command\Definition;

class SubmitScore extends Command
{
    public $victorName;
    public $loserName;
    public $gameName;

    /**
     * @param Definition $definition
     * @param \stdClass|static $options
     */
    static function setUpDefinition(Definition $definition, $options)
    {
        $options->victorName = Command\Option::create()->setType()->setDescription("Winning player name");
        $options->loserName = Command\Option::create()->setType()->setDescription("Losing player name");
        $options->gameName = Command\Option::create()->setType()->setDescription("Game name");
    }

    public function performAction()
    {
        return array($this->victorName, $this->loserName, $this->gameName);
    }


}