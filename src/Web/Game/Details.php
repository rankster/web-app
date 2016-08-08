<?php

namespace Rankster\Web\Game;


use Rankster\Api\ClientException;
use Rankster\Entity\Game as GameEntity;
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

    /**
     * @param Definition $definition
     * @param \stdClass|static $options
     */
    static function setUpDefinition(Definition $definition, $options)
    {
        $options->gameId = Command\Option::create()->setIsRequired()->setDescription('Game ID');
        $options->rankPageId = Command\Option::create()->setDescription('Rank page number');
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
            $this->rankPageId = 0;
        }

        $pages = ceil($game->playersCount / $perPage);
        if ($this->rankPageId > $pages) {
            $this->rankPageId = $pages;
        }

        $ranks = \Rankster\Entity\Rank::getRanks($game->id, $perPage, $this->rankPageId - 1);
        $table = UserRankTable::create();
        $table->byUser = false;
        $table->name = $game->name;
        $table->image = $game->getFullUrl();
        $table->userRanks = $ranks;

        $this->response->addContent((string)$table);

        $this->response->addContent(new Pagination(self::createState($this->io), $this->io, self::options()->rankPageId, $pages));
        self::options()->rankPageId;


        $this->response->addContent('</div>');
    }


}