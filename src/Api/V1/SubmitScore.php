<?php

namespace Rankster\Api\V1;


use Rankster\Api\ClientException;
use Rankster\Entity\Match;
use Rankster\Service\AuthSession;
use Yaoi\Command;
use Yaoi\Command\Definition;

class SubmitScore extends Command
{
    public $opponentId;
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

        $options->opponentId = Command\Option::create()->setType()->setIsRequired();
        $options->gameId = Command\Option::create()->setType()->setIsRequired();
        $options->result = Command\Option::create()->setEnum(Match::RESULT_WIN, Match::RESULT_DRAW, Match::RESULT_LOSE);
    }

    public function performAction()
    {
        if (!$userId = AuthSession::getUserId()) {
            throw new ClientException("Please authorize");
        }

        return Match::make($userId, $this->opponentId, $this->gameId, $this->result)->applyRanks()->toArray();
    }


}