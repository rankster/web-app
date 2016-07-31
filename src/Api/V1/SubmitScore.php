<?php

namespace Rankster\Api\V1;


use Rankster\Entity\Match;
use Yaoi\Command;
use Yaoi\Command\Definition;

class SubmitScore extends Command
{
    public $user1Id;
    public $user2Id;
    public $gameId;
    public $result;

    /**
     * @param Definition $definition
     * @param \stdClass|static $options
     */
    static function setUpDefinition(Definition $definition, $options)
    {
        $definition->name = 'Submit score';
        $definition->description = 'Submits a score for two players at a game';

        $options->user1Id = Command\Option::create()->setType()->setIsRequired();
        $options->user2Id = Command\Option::create()->setType()->setIsRequired();
        $options->gameId = Command\Option::create()->setType()->setIsRequired();
        $options->result = Command\Option::create()->setEnum(Match::RESULT_WIN, Match::RESULT_DRAW, Match::RESULT_LOSE);
    }

    public function performAction()
    {
        return Match::make($this->user1Id, $this->user2Id, $this->gameId, $this->result)->toArray();
    }


}