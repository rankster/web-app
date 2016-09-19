<?php

namespace Rankster\Entity;

use Rankster\Elo\Alcalyn;
use Rankster\Elo\Ranker;
use Rankster\View\SubmitScore\Data;
use Yaoi\Database;
use Yaoi\Database\Definition\Column;
use Yaoi\Database\Definition\Index;
use Yaoi\Database\Entity;
use Yaoi\Migration\ClosureMigration;

class Rank extends Entity
{
    const DEFAULT_RANK = 1200;

    public $id;
    public $gameId;
    public $userId;
    public $groupId;
    public $rank;
    public $lastUpdateTime;
    public $matches = 0;
    public $place = 0;

    /**
     * @param \stdClass|static $columns
     */
    static function setUpColumns($columns)
    {
        $columns->id = Column::AUTO_ID;
        $columns->gameId = Game::columns()->id;
        $columns->userId = User::columns()->id;
        $columns->groupId = Group::columns()->id;
        $columns->rank = Column::FLOAT + Column::SIZE_8B;
        $columns->lastUpdateTime = Column::INTEGER + Column::USE_PHP_DATETIME;
        $columns->matches = Column::INTEGER;
        $columns->place = Column::INTEGER + Column::NOT_NULL;
    }

    /**
     * @param \Yaoi\Database\Definition\Table $table
     * @param \stdClass|static $columns
     */
    static function setUpTable(\Yaoi\Database\Definition\Table $table, $columns)
    {
        $table->setSchemaName('rank')
            ->addIndex(Index::TYPE_UNIQUE, $columns->gameId, $columns->userId, $columns->groupId);
        Column::cast($columns->groupId)->setDefault(0);
    }

    public function draw(Rank $opponent)
    {
        self::$ranker->update($this, $opponent, true);
    }

    public function win(Rank $loser)
    {
        self::$ranker->update($this, $loser);
    }


    public static function findOrCreateByUserGame($userId, $gameId, $groupId)
    {
        $rank = new Rank();
        $rank->userId = $userId;
        $rank->gameId = $gameId;
        $rank->groupId = $groupId;
        if ($saved = $rank->findSaved()) {
            return $saved;
        } else {
            $rank->rank = self::DEFAULT_RANK;
            GameGroup::incrementPlayersCount($rank->gameId, $rank->groupId);
        }

        $rank->save();
        return $rank;
    }

    public function save()
    {
        $this->lastUpdateTime = new \DateTime();
        parent::save();

        $history = new RankHistory();
        $history->userId = $this->userId;
        $history->gameId = $this->gameId;
        $history->rank = $this->show();
        $history->time = time();
        $history->save();
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

    public static function getRanksByUser($userId, $perPage = 20, $page = 0)
    {
        $st = Rank::statement()
            ->where('? = ?', Rank::columns()->userId, $userId)
            ->order('? DESC', Rank::columns()->rank)
            ->innerJoin('? ON (? = ?)', User::table(), User::columns()->id, Rank::columns()->userId)
            ->limit($perPage, $perPage * $page);

        $query = $st->query();
        $query->bindResultClass();

        return $query->fetchAll();
    }

    /** @var Ranker */
    private static $ranker;

    public static function setRanker(Ranker $ranker)
    {
        self::$ranker = $ranker;
    }

    public static function getRanker()
    {
        return self::$ranker;
    }

    public function show()
    {
        return round($this->rank);
    }

    public function updatePlaces()
    {
        $db = Database::getInstance();
        $db->query('set @i=0');
        $db->query(
            <<<SQL
INSERT INTO :table (:id, :place, :game_id, :user_id, :group_id)
  (SELECT :id, @i := @i + 1, :game_id, :user_id, :group_id FROM :table 
  WHERE :game_id = :game_id_val AND :group_id = :group_id_val ORDER BY :rank DESC) 
ON DUPLICATE KEY UPDATE :place = VALUES(:place)
SQL
            , array(
                'table' => Rank::table(),
                'user_id' => Rank::columns()->userId,
                'id' => Rank::columns()->id,
                'place' => Rank::columns()->place,
                'game_id' => Rank::columns()->gameId,
                'group_id' => Rank::columns()->groupId,
                'rank' => Rank::columns()->rank,
                'game_id_val' => $this->gameId,
                'group_id_val' => $this->groupId,
            )
        )->execute();
        return $this;
    }


    public static function mig1ration()
    {
        $migration = parent::migration();
        return new ClosureMigration(
            'noid',
            function () use ($migration) {
                Rank::statement()
                    ->update()
                    ->set('? = 0', Rank::columns()->groupId)
                    ->where('? IS NULL', Rank::columns()->groupId)
                    ->query()
                    ->execute();
                $migration->apply();
            },
            function () use ($migration) {
                $migration->rollback();
            },
            true
        );
    }

}

Rank::setRanker(new Alcalyn());
