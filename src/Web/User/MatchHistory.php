<?php

namespace Rankster\Web\User;


use Rankster\Entity\Game;
use Rankster\Entity\Match;
use Rankster\Entity\Rank;
use Rankster\Entity\User;
use Yaoi\Command;
use Yaoi\Command\Definition;

class MatchHistory extends Command
{
    public $gameId;
    public $userId;

    /**
     * @param Definition $definition
     * @param \stdClass|static $options
     */
    static function setUpDefinition(Definition $definition, $options)
    {
        $options->gameId = Command\Option::create()->setType()->setIsRequired();
        $options->userId = Command\Option::create()->setType()->setIsRequired();
    }

    public function performAction()
    {
        $game = Game::findByPrimaryKey($this->gameId);
        if (!$game) {
            $this->response->error("Game not found");
            return;
        }

        $user = User::findByPrimaryKey($this->userId);
        if (!$user) {
            $this->response->error("User not found");
            return;
        }

        $rank = Rank::findOrCreateByUserGame($this->userId, $this->gameId);

        $rows = Match::statement()
            ->where('? = ?', Match::columns()->gameId, $this->gameId)
            ->where('? IN (?, ?)', $this->userId, Match::columns()->user1Id, Match::columns()->user2Id)
            ->order('? DESC', Match::columns()->id)
            ->query();


        $this->response->addContent(new \Rankster\View\Match\MatchHistory($rows, $game, $user));
    }


}