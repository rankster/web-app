<?php

namespace Rankster\Web\Match;

use Rankster\Entity\Match;
use Rankster\Entity\Rank;
use Yaoi\Command;
use Yaoi\Command\Definition;

class Create extends Command
{
    const RESULT_WIN = 'win';
    const RESULT_LOSE = 'lose';
    const RESULT_DRAW = 'draw';

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
            ->setEnum(self::RESULT_WIN, self::RESULT_LOSE, self::RESULT_DRAW)
            ->setDescription('Match result');
    }

    public function performAction()
    {

        if (empty($_SESSION['user_id'])) {
            $this->response->error("Please authorize");
            return;
        }

        //$this->response->addContent('<pre>' . print_r($_SESSION, 1) . '</pre>');

        $match = new Match();
        $match->user1Id = $_SESSION['user_id'];
        $match->user2Id = $this->opponentId;
        $match->gameId = $this->gameId;

        $rank1 = Rank::findOrCreateByUserGame($match->user1Id, $this->gameId);
        $rank2 = Rank::findOrCreateByUserGame($match->user2Id, $this->gameId);

        $match->eventTime = time();
        $match->status = Match::STATUS_ACCEPT;
        if ($this->result === self::RESULT_DRAW) {
            $match->winnerId = null;
            $rank1->draw($rank2);
        } elseif ($this->result === self::RESULT_WIN) {
            $rank1->win($rank2);
            $match->winnerId = $match->user1Id;
        } else {
            $rank2->win($rank1);
            $match->winnerId = $match->user2Id;
        }

        $rank1->save();
        $rank2->save();
        $match->save();
    }


}