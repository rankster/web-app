<?php

namespace Rankster\Web\Game;


use Rankster\Api\ClientException;
use Rankster\Entity\Game as GameEntity;
use Rankster\Entity\Match;
use Rankster\View\History;
use Rankster\View\Pagination;
use Rankster\View\UserRankTable;
use Yaoi\Command;
use Yaoi\Command\Definition;
use Yaoi\Io\Content\Anchor;
use Yaoi\Io\Content\Heading;

class Details extends Command
{
    public $gameId;
    public $rankPageId;
    public $historyPageId;

    /**
     * @param Definition $definition
     * @param \stdClass|static $options
     */
    static function setUpDefinition(Definition $definition, $options)
    {
        $options->gameId = Command\Option::create()->setIsRequired()->setDescription('Game ID');
        $options->rankPageId = Command\Option::create()->setDescription('Rank page number');
        $options->historyPageId = Command\Option::create()->setDescription('History page number');
    }

    public function performAction()
    {
        $game = GameEntity::findByPrimaryKey($this->gameId);
        if (!$game) {
            throw new ClientException("Game not found");
        }
        $this->response->addContent(new Heading($game->name));

        $this->response->addContent('<div class="row">');

        $perPage = 20;

        if (!$this->rankPageId) {
            $this->rankPageId = 1;
        }

        $pages = ceil($game->playersCount / $perPage);
        if ($this->rankPageId > $pages) {
            $this->rankPageId = $pages;
        }

        $commandState = self::createState($this->io);

        $ranks = \Rankster\Entity\Rank::getRanks($game->id, $perPage, $this->rankPageId - 1);
        $table = UserRankTable::create();
        $table->byUser = false;
        $table->name = "Rank";
        $table->image = $game->getFullUrl();
        $table->userRanks = $ranks;
        $table->setPagination(new Pagination($commandState->copy(), self::options()->rankPageId, $pages));
        $this->response->addContent((string)$table);


        if (!$this->historyPageId) {
            $this->historyPageId = 1;
        }

        $pages = ceil($game->matchesCount / $perPage);
        $matches = Match::statement()
            ->where('? = ?', Match::columns()->gameId, $this->gameId)
            ->order('? DESC', Match::columns()->eventTime)
            ->limit($perPage, $perPage * ($this->historyPageId - 1))
            ->query()
            ->fetchAll();

        $history = new History($matches);
        $history->setPagination(new Pagination($commandState, self::options()->historyPageId, $pages));
        $this->response->addContent($history);

        $this->response->addContent('</div>');
    }


}