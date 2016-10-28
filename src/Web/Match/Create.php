<?php

namespace Rankster\Web\Match;

use Rankster\Entity\Match as MatchEntity;
use Rankster\Entity\MatchRequest;
use Rankster\Entity\Rank as RankEntity;
use Rankster\Entity\WhiteList;
use Rankster\Service\AuthSession;
use Rankster\Service\Session;
use Rankster\Web\User\MatchHistory;
use Yaoi\Command;
use Yaoi\Command\Definition;
use Rankster\Entity\User as UserEntity;

class Create extends Command
{
    public $count = 1;
    public $opponentId;
    public $gameId;
    public $result;

    /**
     * @param Definition $definition
     * @param \stdClass|static $options
     */
    static function setUpDefinition(Definition $definition, $options)
    {
        $options->count = Command\Option::create()->setType()->setDescription('Matches count, default 1');
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
            for ($i = 0; $i < $this->count; ++$i) {
                MatchEntity::make($userId, $this->opponentId, $this->gameId, $this->result)->applyRanks();
            }
            /** @var MatchHistory $matchHistory */
            $matchHistory = MatchHistory::createState();
            $matchHistory->gameId = $this->gameId;
            $matchHistory->userId = $userId;
            $url = (string)$this->io->makeAnchor($matchHistory);
            header("Location: $url");
        } else {
            for ($i = 0; $i < $this->count; ++$i) {
                $mr = MatchRequest::make($userId, $this->opponentId, $this->gameId, $this->result);
                $mr->save();
            }

            $opponent = UserEntity::findByPrimaryKey($this->opponentId);
            Session::addSuccessMessage(
                'Your match result is stored. Your rank will be changed once ' .
                $opponent->name . ' confirms this match'
            );

            header('Location: /');
        }
    }

}