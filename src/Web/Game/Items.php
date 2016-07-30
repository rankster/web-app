<?php

namespace Rankster\Web\Game;


use Rankster\Entity\Game;
use Yaoi\Command;
use Yaoi\Command\Definition;
use Yaoi\Io\Content\Anchor;
use Yaoi\Io\Content\Rows;
use Yaoi\Rows\Processor;

class Items extends Command
{
    static function setUpDefinition(Definition $definition, $options)
    {
    }

    public function performAction()
    {
        $games = Game::statement()->query()->fetchAll();
        $gameState = Details::createState();

        $this->response->addContent(new Rows(Processor::create($games)->map(
            function (Game $game) use ($gameState) {
                $row = array();
                $gameState->gameId = $game->id;
                $row['Name'] = new Anchor($game->name, $this->io->makeAnchor($gameState));
                return $row;
            }
        )));
    }


}