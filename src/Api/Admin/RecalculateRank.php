<?php

namespace Rankster\Api\Admin;


use Rankster\Entity\Game;
use Rankster\Entity\RankHistory;
use Rankster\Manager\GameManager;
use Yaoi\Command;
use Yaoi\Command\Definition;
use Yaoi\Database;

class RecalculateRank extends Command
{
    public $gameId;

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

        GameManager::rebuildRanks($gameIds);

        return "done";
    }


}