<?php

namespace Rankster\Web\Game;


use Rankster\Entity\Game as GameEntity;
use Rankster\View\GamePlate;
use Yaoi\Command;
use Yaoi\Command\Definition;
use Yaoi\Io\Content\Heading;

class Items extends Command
{
    static function setUpDefinition(Definition $definition, $options)
    {
    }

    public function performAction()
    {
        $games = GameEntity::statement()->order('? DESC', GameEntity::columns()->playersCount)->query()->fetchAll();

        $this->response->addContent(new Heading("Games"));

        $this->response->addContent('<div class="row">');
        foreach ($games as $game) {
            $this->response->addContent(new GamePlate($game));
        }

        $this->response->addContent('</div>');

    }


}