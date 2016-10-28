<?php

namespace Rankster\Api\Admin;


use Rankster\Entity\Game;
use Rankster\Manager\GameManager;
use Yaoi\Command;
use Yaoi\Command\Definition;
use Yaoi\Database;

class WipeRank extends Command
{
    public $gameId = 0;

    /**
     * @param Definition $definition
     * @param \stdClass|static $options
     */
    static function setUpDefinition(Definition $definition, $options)
    {
        $options->gameId = Command\Option::create()->setType();
    }

    public function performAction()
    {
        if ($this->gameId) {
            $gameIds = array($this->gameId);
        } else {
            $gameIds = Game::statement()->bindResultClass()->query()->fetchAll(null, 'id');
        }

        GameManager::wipeMatches($gameIds);

        return "done";
    }


}