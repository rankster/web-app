<?php

namespace Rankster\Web\Match;


use Rankster\Entity\Match;
use Yaoi\Command;
use Yaoi\Command\Definition;

class Details extends Command
{
    public $matchId;

    /**
     * @param Definition $definition
     * @param \stdClass|static $options
     */
    static function setUpDefinition(Definition $definition, $options)
    {
        $options->matchId = Command\Option::create()->setType()->setIsRequired()
            ->setDescription('Match ID');
    }

    public function performAction()
    {
        $match = Match::findByPrimaryKey($this->matchId);



    }


}