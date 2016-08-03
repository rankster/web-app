<?php

namespace Rankster\Manager;


use Rankster\Entity\Match;
use Rankster\Entity\Rank;
use Rankster\Entity\RankHistory;
use Rankster\Entity\User;

class UserManager
{
    public static function deleteUser(User $user)
    {
        $gameIds = $user->getGameIds();
        Rank::statement()->delete()->where('? = ?', Rank::columns()->userId, $user->id)->query()->execute();
        RankHistory::statement()->delete()->where('? = ?', RankHistory::columns()->userId, $user->id)->query()->execute();
        Match::statement()->delete()->where('? IN (?, ?)', $user->id, Match::columns()->user1Id, Match::columns()->user2Id);

        foreach ($gameIds as $gameId) {
            GameManager::rebuildRanks($gameId);
        }
    }

}