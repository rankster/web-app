<?php

namespace Rankster\Web\Match;


use Rankster\Entity\Match as MatchEntity;
use Rankster\View\MatchDetails;
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
        $match = MatchEntity::findByPrimaryKey($this->matchId);
        if (!$match) {
            $this->response->error("Match not found");
            return;
        }

        $this->response->addContent(new MatchDetails($match));
    }


}