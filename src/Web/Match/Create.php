<?php

namespace Rankster\Web\Match;

use Rankster\Entity\Match as MatchEntity;
use Rankster\Entity\MatchRequest;
use Rankster\Entity\Rank as RankEntity;
use Rankster\Entity\WhiteList;
use Rankster\Service\AuthSession;
use Yaoi\Command;
use Yaoi\Command\Definition;
use Rankster\Entity\User as UserEntity;

class Create extends Command
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
        $options->opponentId = Command\Option::create()->setType()->setIsRequired()->setDescription('Opponent user ID');
        $options->gameId = Command\Option::create()->setType()->setIsRequired()->setDescription('Game ID');
        $options->result = Command\Option::create()
            ->setEnum(MatchEntity::RESULT_WIN, MatchEntity::RESULT_LOSE, MatchEntity::RESULT_DRAW)
            ->setDescription('Match result');
    }

    public function performAction()
    {

        if (!$userId = AuthSession::getUserId()) {
            $this->response->error("Please authorize");
            return;
        }

        if (WhiteList::isWhiteListed($userId, $this->opponentId, $this->gameId)) {
            $match = MatchEntity::make($userId, $this->opponentId, $this->gameId, $this->result)->applyRanks();
            $details = Details::createState();
            $details->matchId = $match->id;
            $url = (string)$this->io->makeAnchor($details);
            header("Location: $url");
        } else {
            $mr = MatchRequest::make($userId, $this->opponentId, $this->gameId, $this->result);
            $mr->save();

            $opponent = UserEntity::findByPrimaryKey($this->opponentId);
            AuthSession::addSuccessMessage(
                'Your match result is stored. Your rank will be changed once ' .
                $opponent->name . ' confirms this match'
            );

            header('Location: /');
        }
    }

}