<?php

namespace Rankster\Web\Game;


use Rankster\Api\ClientException;
use Rankster\Entity\Game as GameEntity;
use Yaoi\Command;
use Yaoi\Command\Definition;
use Yaoi\Io\Content\Heading;

class Details extends Command
{
    public $gameId;

    /**
     * @param Definition $definition
     * @param \stdClass|static $options
     */
    static function setUpDefinition(Definition $definition, $options)
    {
        $options->gameId = Command\Option::create()->setIsRequired()->setDescription('Game ID');
    }

    public function performAction()
    {
        $game = GameEntity::findByPrimaryKey($this->gameId);
        if (!$game) {
            throw new ClientException("Game not found");
        }
        $this->response->addContent(new Heading($game->name));


        $this->response->addContent(print_r($game->toArray(), 1));
    }


}