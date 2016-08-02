<?php

namespace Rankster\Entity;

use Rankster\Elo\Alcalyn;
use Rankster\Elo\Ranker;
use Yaoi\Database\Definition\Column;
use Yaoi\Database\Definition\Index;
use Yaoi\Database\Entity;

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
        $columns->rank = Column::FLOAT + Column::SIZE_8B;
        $columns->lastUpdateTime = Column::INTEGER + Column::USE_PHP_DATETIME;
        $columns->matches = Column::INTEGER;
    }

    /**
     * @param \Yaoi\Database\Definition\Table $table
     * @param \stdClass|static $columns
     */
    static function setUpTable(\Yaoi\Database\Definition\Table $table, $columns)
    {
        $table->setSchemaName('rank')->addIndex(Index::TYPE_UNIQUE, $columns->gameId, $columns->userId);
    }

    public function draw(Rank $opponent)
    {
        self::$ranker->update($this, $opponent, true);
    }

    public function win(Rank $loser)
    {
        self::$ranker->update($this, $loser);
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
        $this->lastUpdateTime = new \DateTime();
        parent::save();

        $history = new RankHistory();
        $history->userId = $this->userId;
        $history->gameId = $this->gameId;
        $history->rank = $this->rank;
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
}

Rank::setRanker(new Alcalyn());
