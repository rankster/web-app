<?php

namespace Rankster\Entity;

use Yaoi\Database\Definition\Column;
use Yaoi\Database\Definition\Table;
use Yaoi\Database\Entity;

class Match extends Entity
{
    const STATUS_ACCEPT = 'accepted';
    const STATUS_REJECT = 'rejected';
    const RESULT_LOSE = 'lose';
    const RESULT_DRAW = 'draw';
    const RESULT_WIN = 'win';

    public $id;
    public $gameId;
    public $user1Id;
    public $user2Id;
    public $user1Delta;
    public $user2Delta;

    public $user1NewRank;
    public $user2NewRank;


    public $winnerId;
    /** @var \DateTime */
    public $eventTime;
    public $status;

    /**
     * @param \stdClass|static $columns
     */
    static function setUpColumns($columns)
    {
        $columns->id = Column::AUTO_ID;
        $columns->gameId = Game::columns()->id;
        $columns->user1Id = User::columns()->id;
        $columns->user2Id = User::columns()->id;
        $columns->winnerId = User::columns()->id;
        $columns->user1Delta = Column::INTEGER + Column::NOT_NULL;
        $columns->user2Delta = Column::INTEGER + Column::NOT_NULL;
        $columns->user1NewRank = Column::INTEGER + Column::NOT_NULL;
        $columns->user2NewRank = Column::INTEGER + Column::NOT_NULL;
        $columns->status = Column::STRING;
        $columns->eventTime = Column::INTEGER + Column::USE_PHP_DATETIME;
    }

    /**
     * @param Table $table
     * @param \stdClass|static $columns
     */
    static function setUpTable(\Yaoi\Database\Definition\Table $table, $columns)
    {
        Column::cast($columns->winnerId)->setFlag(Column::NOT_NULL, false);
        $table->setSchemaName('game_match');
    }

    public static function getTotalWins($userId)
    {
        $mCols = Match::columns();

        $stat = Match::statement()
            ->select('COUNT(1) AS total, SUM(IF(? = ?, 1, 0)) AS wins', $mCols->winnerId, $userId)
            ->where('? > ?', $mCols->eventTime, time() - 7 * 86400)
            ->where("? IN (?, ?)", $userId, $mCols->user1Id, $mCols->user2Id)->bindResultClass()
            ->query()->fetchRow();

        return [
            "total" => $stat['total'],
            "percents" => $stat['total'] ? round(100 * $stat['wins'] / $stat['total']) : 0
        ];
    }

    public function applyRanks()
    {
        $groupIds = UserGroup::getCommonGroupIds($this->user1Id, $this->user2Id);
        $this->applyRanksByGroup(0);
        foreach ($groupIds as $groupId) {
            if ($groupId) {
                $this->applyRanksByGroup($groupId);
            }
        }
        return $this;
    }

    public function applyRanksByGroup($groupId = 0)
    {
        $rank1 = Rank::findOrCreateByUserGame($this->user1Id, $this->gameId, $groupId);
        $rank2 = Rank::findOrCreateByUserGame($this->user2Id, $this->gameId, $groupId);

        $r1 = $rank1->rank;
        $r2 = $rank2->rank;

        if ($this->winnerId === null) {
            $rank1->draw($rank2);
        } elseif ($this->winnerId === $this->user1Id) {
            $rank1->win($rank2);
        } else {
            $rank2->win($rank1);
        }

        $this->user1Delta = round($rank1->rank - $r1);
        $this->user2Delta = round($rank2->rank - $r2);

        $this->user1NewRank = round($rank1->rank);
        $this->user2NewRank = round($rank2->rank);

        $rank1->matches++;
        $rank2->matches++;

        GameGroup::incrementMatchesCount($this->gameId, $groupId);

        $user1 = User::findByPrimaryKey($this->user1Id);
        $user1->matchesCount++;
        $user1->save();

        $user2 = User::findByPrimaryKey($this->user2Id);
        $user2->matchesCount++;
        $user2->save();

        $rank1->save();
        $rank2->save();
        $this->save();

        $rank1->updatePlaces();
        return $this;
    }

    public static function make($user1Id, $user2Id, $gameId, $result)
    {
        $match = new static();
        $match->user1Id = $user1Id;
        $match->user2Id = $user2Id;
        $match->gameId = $gameId;
        $match->eventTime = new \DateTime();

        $match->status = static::STATUS_ACCEPT;
        if ($result === static::RESULT_DRAW) {
            $match->winnerId = null;
        } elseif ($result === static::RESULT_WIN) {
            $match->winnerId = $match->user1Id;
        } else {
            $match->winnerId = $match->user2Id;
        }

        return $match;
    }

}
