<?php

namespace Rankster\View;

use Rankster\Entity\Game;
use Rankster\Entity\Match;
use Rankster\Entity\Rank;
use Rankster\Entity\User;
use Yaoi\View\Hardcoded;

class MatchDetails extends Hardcoded
{
    public function __construct(Match $match)
    {
        $this->match = $match;
    }

    public $match;

    public function render()
    {
        $match = $this->match;
        $user1 = User::findByPrimaryKey($match->user1Id);
        $user2 = User::findByPrimaryKey($match->user2Id);

        $rank1 = Rank::findOrCreateByUserGame($user1->id, $match->gameId);
        $rank2 = Rank::findOrCreateByUserGame($user2->id, $match->gameId);

        $gamePlate = (string)GamePlate::create(Game::findByPrimaryKey($match->gameId));
        $user1Plate = (string)UserPlate::create($user1, $rank1->rank);
        $user2Plate = (string)UserPlate::create($user2, $rank2->rank);

        echo <<<HTML
<div class="row">
{$gamePlate}
{$user1Plate}
{$user2Plate}
</div>
HTML;
    }


}