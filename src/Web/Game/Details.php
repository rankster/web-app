<?php

namespace Rankster\Web\Game;


use Rankster\Entity\Game;
use Yaoi\Command;
use Yaoi\Command\Definition;

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
        $game = Game::findByPrimaryKey($this->gameId);

        $this->response->addContent(print_r($game->toArray()));
    }


}