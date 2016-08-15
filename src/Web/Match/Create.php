<?php

namespace Rankster\Web\Match;

use Rankster\Entity\Match as MatchEntity;
use Rankster\Entity\Rank as RankEntity;
use Rankster\Service\AuthSession;
use Yaoi\Command;
use Yaoi\Command\Definition;

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

        //$this->response->addContent('<pre>' . print_r($_SESSION, 1) . '</pre>');

        $match = MatchEntity::make($userId, $this->opponentId, $this->gameId, $this->result);
        if ($match->status > 0) {
            $match->applyRanks();
        } else {
            $match->save();
        }

        $details = Details::createState();
        $details->matchId = $match->id;
        $url = (string)$this->io->makeAnchor($details);
        header("Location: $url");
    }


}