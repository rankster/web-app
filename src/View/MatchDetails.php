<?php

namespace Rankster\View;

use Rankster\Entity\Match;
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

        $user1Plate = (string)UserPlate::create($user1);
        $user2Plate = (string)UserPlate::create($user2);

        echo <<<HTML
<div class="row">
{$user1Plate}
{$user2Plate}
</div>
HTML;
    }


}