<?php

namespace Rankster\Entity;

use Alcalyn\Elo\EloSystem;
use Yaoi\Database\Definition\Column;
use Yaoi\Database\Entity;

require_once __DIR__ . '/../../vendor/alcalyn/elo/src/Exception/EloCoefficientException.php';
require_once __DIR__ . '/../../vendor/alcalyn/elo/src/EloSystem.php';

class Rank extends Entity
{
    const DEFAULT_RANK = 1200;

    public $id;
    public $gameId;
    public $userId;
    public $rank;
    public $lastUpdateTime;
    public $matches = 0;

    /**
     * @param \stdClass|static $columns
     */
    static function setUpColumns($columns)
    {
        $columns->id = Column::AUTO_ID;
        $columns->gameId = Game::columns()->id;
        $columns->userId = User::columns()->id;
        $columns->rank = Column::INTEGER;
        $columns->lastUpdateTime = Column::INTEGER;
        $columns->matches = Column::INTEGER;
    }

    static function setUpTable(\Yaoi\Database\Definition\Table $table, $columns)
    {
        $table->setSchemaName('rank');
    }

    public function draw(Rank $opponent)
    {
        $eloSystem = new EloSystem();

        $updatedElos = $eloSystem->draw($this->rank, $opponent->rank);
        $this->rank = $updatedElos[0];
        $opponent->rank = $updatedElos[1];
    }

    public function win(Rank $loser)
    {
        $eloSystem = new EloSystem();

        $updatedElos = $eloSystem->calculate($this->rank, $loser->rank, 1);

        $this->rank = $updatedElos[0];
        $loser->rank = $updatedElos[1];
    }


    public static function findOrCreateByUserGame($userId, $gameId) {
        $rank = new Rank();
        $rank->userId = $userId;
        $rank->gameId = $gameId;
        if ($saved = $rank->findSaved()) {
            return $saved;
        } else {
            $rank->rank = self::DEFAULT_RANK;
        }

        $rank->save();
        return $rank;
    }

    public function save()
    {
        $this->lastUpdateTime = time();
        $history = new RankHistory();
        $history->userId = $this->userId;
        $history->gameId = $this->gameId;
        $history->rank = $this->rank;
        $history->time = time();
        $history->save();

        parent::save();
    }

    public static function getRanks($gameId, $perPage = 20, $page = 0)
    {
        $st = Rank::statement()
            ->where('? = ?', Rank::columns()->gameId, $gameId)
            ->order('? DESC', Rank::columns()->rank)
            ->innerJoin('? ON (? = ?)', User::table(), User::columns()->id, Rank::columns()->userId)
            ->limit($perPage, $perPage * $page);

        $query = $st->query();
        $query->bindResultClass();

        return $query->fetchAll();
    }
}
