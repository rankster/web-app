<?php

namespace Rankster\Api\V1;


use Rankster\Entity\Game;
use Yaoi\Command;
use Yaoi\Command\Definition;


class AddGame extends Command
{

    public $name;
    public $picturePath;

    /**
     * @param Definition $definition
     * @param \stdClass|static $options
     */
    static function setUpDefinition(Definition $definition, $options)
    {
        $options->name = Command\Option::create()->setType()->setIsRequired()->setDescription('Game name');
        $options->picturePath = Command\Option::create()->setType()->setIsRequired()->setDescription('Picture path');
    }

    public function performAction()
    {
        $game = new Game();
        $game->name = $this->name;
        $game->picturePath = $this->picturePath;
        $game->findOrSave();
        return $game->toArray();
    }


}