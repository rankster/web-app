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
    public $winnerId;
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
        $columns->user1Delta = Column::INTEGER;
        $columns->user2Delta = Column::INTEGER;
        $columns->status = Column::STRING;
        $columns->eventTime = Column::INTEGER;
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

    public function getTotalWins($userEmail) {
        $match = new Match();

        $totalMatch = $match->statement()->
        where("?=? OR ?=?", $match->columns()->user1Id, $_SESSION['user_id'], $match->columns()->user2Id, $_SESSION['user_id'])->
        query()->fetchAll();

        $matchLost = $match->statement()->
        where("?=? OR ?=?", $match->columns()->user1Id, $_SESSION['user_id'], $match->columns()->user2Id, $_SESSION['user_id'])->
            where("? != ?", $match->columns()->winnerId, $_SESSION['user_id'])->
            query()->fetchAll();


        $matchWin = count($totalMatch) - count($matchLost);
        $percents = ($matchWin / count($totalMatch)) * 100;

        return ["total" => count($totalMatch), "percents" => round($percents)];
    }

    public static function make($user1Id, $user2Id, $gameId, $result) {
        $match = new Match();
        $match->user1Id = $user1Id;
        $match->user2Id = $user2Id;
        $match->gameId = $gameId;

        $rank1 = Rank::findOrCreateByUserGame($match->user1Id, $gameId);
        $rank2 = Rank::findOrCreateByUserGame($match->user2Id, $gameId);

        $r1 = $rank1->rank;
        $r2 = $rank2->rank;

        $match->eventTime = time();
        $match->status = Match::STATUS_ACCEPT;
        if ($result === self::RESULT_DRAW) {
            $match->winnerId = null;
            $rank1->draw($rank2);
        } elseif ($result === self::RESULT_WIN) {
            $rank1->win($rank2);
            $match->winnerId = $match->user1Id;
        } else {
            $rank2->win($rank1);
            $match->winnerId = $match->user2Id;
        }

        $match->user1Delta = $rank1->rank - $r1;
        $match->user2Delta = $rank2->rank - $r2;

        $rank1->matches++;
        $rank2->matches++;

        $rank1->save();
        $rank2->save();
        $match->save();

        return $match;
    }
}
