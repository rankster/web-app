<?php

namespace Rankster\Manager;


use Rankster\Entity\Match;
use Rankster\Entity\Rank;
use Rankster\Entity\RankHistory;
use Yaoi\Database;

class GameManager
{
    public static function wipeRanks(array $gameIds)
    {
        Database::getInstance()->query("DELETE FROM ? WHERE ? IN (?)", Rank::table(), Rank::columns()->gameId, $gameIds)
            ->execute();

        Database::getInstance()
            ->query("DELETE FROM ? WHERE ? IN (?)", RankHistory::table(), RankHistory::columns()->gameId, $gameIds)
            ->execute();
    }

    public static function wipeMatches(array $gameIds)
    {
        self::wipeRanks($gameIds);

        Database::getInstance()
            ->query("DELETE FROM ? WHERE ? IN (?)", Match::table(), Match::columns()->gameId, $gameIds)
            ->execute();

    }

    public static function rebuildRanks($gameId)
    {
        $res = Match::statement()
            ->where('? = ?', Match::columns()->gameId, $gameId)
            ->order('? ASC', Match::columns()->id)->query();
        /** @var Match $row */
        foreach ($res as $row) {
            $row->applyRanks();
        }

    }

}