<?php

namespace Rankster\Web\User;


use Rankster\Entity\Game;
use Rankster\Entity\Match;
use Rankster\Entity\Rank;
use Rankster\Entity\User as UserEntity;
use Rankster\View\GamePlate;
use Rankster\View\History;
use Rankster\View\Pagination;
use Rankster\View\UserPlate;
use Yaoi\Command;
use Yaoi\Command\Definition;
use Yaoi\Io\Content\Heading;

class MatchHistory extends Command
{
    public $gameId;
    public $userId;
    public $historyPage;

    /**
     * @param Definition $definition
     * @param \stdClass|static $options
     */
    static function setUpDefinition(Definition $definition, $options)
    {
        $options->gameId = Command\Option::create()->setType();
        $options->userId = Command\Option::create()->setType()->setIsRequired();
        $options->historyPage = Command\Option::create()->setType();
    }

    public function performAction()
    {
        $game = Game::findByPrimaryKey($this->gameId);
        if (!$game) {
            $this->response->error("Game not found");
            return;
        }

        $user = UserEntity::findByPrimaryKey($this->userId);
        if (!$user) {
            $this->response->error("User not found");
            return;
        }

        $rank = Rank::findOrCreateByUserGame($this->userId, $this->gameId);

        /*
        $rows = Match::statement()
            ->where('? = ?', Match::columns()->gameId, $this->gameId)
            ->where('? IN (?, ?)', $this->userId, Match::columns()->user1Id, Match::columns()->user2Id)
            ->order('? DESC', Match::columns()->id)
            ->query();
*/

        $commandState = self::createState($this->io);

        $perPage = 12;
        $pages = ceil($rank->matches / $perPage);

        if ($this->historyPage > $pages) {
            $this->historyPage = $pages;
        }

        if (!$this->historyPage) {
            $this->historyPage = 1;
        }

        $matches = Match::statement()
            ->where('? IN (?, ?)', $this->userId, Match::columns()->user1Id, Match::columns()->user2Id)
            ->order('? DESC', Match::columns()->eventTime)
            ->limit($perPage, $perPage * ($this->historyPage - 1));

        if ($this->gameId) {
            $matches->where('? = ?', Match::columns()->gameId, $this->gameId);
        }

        $matches = $matches
            ->query()
            ->fetchAll();

        $history = new History($matches);
        $history->title = 'Match History';
        $history->setPagination(new Pagination($commandState, self::options()->historyPage, $pages));

        $gamePlate = new GamePlate($game);
        $info = 'Rank: ' . $rank->show();
        $userPlate = new UserPlate($user, $info);

        $this->response->addContent('<div class="row">');
        $this->response->addContent($gamePlate);
        $this->response->addContent($userPlate);
        $this->response->addContent('</div>');

        $this->response->addContent(new Heading('History'));
        $this->response->addContent('<div class="row">');
        $this->response->addContent($history);
        $this->response->addContent('</div>');


        //$this->response->addContent(new \Rankster\View\Match\MatchHistory($rows, $game, $user, $rank));
    }


}