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
    /** @var int|Command\Option */
    public $rankPage = 0;
    /** @var int|Command\Option */
    public $historyPage = 0;

    /**
     * @param Definition $definition
     * @param \stdClass|static $options
     */
    static function setUpDefinition(Definition $definition, $options)
    {
        $options->gameId = Command\Option::create()->setType()->setIsRequired()->setDescription('Game ID');
        $options->rankPage = Command\Option::create()->setType()->setDescription('Rank page number');
        $options->historyPage = Command\Option::create()->setType()->setDescription('History page number');
    }

    public function performAction()
    {
        $game = GameEntity::findByPrimaryKey($this->gameId);

        if (!$game) {
            throw new ClientException("Game not found");
        }
        $this->response->addContent(new Heading($game->name));

        $this->response->addContent('<div class="row">');

        $perPage = 3;

        $pages = ceil($game->playersCount / $perPage);

        if ($this->rankPage > $pages) {
            $this->rankPage = $pages;
        }

        if (!$this->rankPage) {
            $this->rankPage = 1;
        }

        $commandState = self::createState($this->io);

        $ranks = \Rankster\Entity\Rank::getRanks($game->id, $perPage, $this->rankPage - 1);
        $table = UserRankTable::create();
        $table->name = "Rank";
        $table->image = $game->getFullUrl();
        $table->game = $game;
        $table->userRanks = $ranks;
        $table->setPagination(new Pagination($commandState->copy(), self::options()->rankPage, $pages));
        $this->response->addContent((string)$table);


        $perPage = 12;
        $pages = ceil($game->matchesCount / $perPage);

        if ($this->historyPage > $pages) {
            $this->historyPage = $pages;
        }

        if (!$this->historyPage) {
            $this->historyPage = 1;
        }

        $matches = Match::statement()
            ->where('? = ?', Match::columns()->gameId, $this->gameId)
            ->order('? DESC', Match::columns()->eventTime)
            ->limit($perPage, $perPage * ($this->historyPage - 1))
            ->query()
            ->fetchAll();

        $history = new History($matches);
        $history->title = 'Match History';
        $history->setPagination(new Pagination($commandState, self::options()->historyPage, $pages));
        $this->response->addContent($history);

        $this->response->addContent('</div>');
    }


}