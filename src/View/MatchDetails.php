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
        $user1Info = $rank1->rank;
        $user2Info = $rank2->rank;

        if ($match->winnerId === null) {
            $user1Info .= ' draw';
            $user2Info .= ' draw';
        } elseif ($match->winnerId === $user1->id) {
            $user1Info .= ' win';
            $user2Info .= ' lose';
        } else {
            $user1Info .= ' lose';
            $user2Info .= ' win';
        }

        $user1Plate = (string)UserPlate::create($user1, $user1Info);
        $user2Plate = (string)UserPlate::create($user2, $user2Info);

        echo <<<HTML
<div class="row">
{$gamePlate}
</div>
<div class="row">
{$user1Plate}
{$user2Plate}
</div>
HTML;
    }


}