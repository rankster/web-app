<?php

namespace Rankster\View\Match;


use Rankster\Entity\Game;
use Rankster\Entity\Match;
use Rankster\Entity\Rank;
use Rankster\Entity\User;
use Rankster\View\GamePlate;
use Rankster\View\UserPlate;
use Yaoi\View\Hardcoded;

class MatchHistory extends Hardcoded
{

    /** @var Match[] */
    private $rows;
    /** @var Game */
    private $game;
    /** @var User */
    private $user;
    /** @var Rank */
    private $rank;

    public function __construct($rows, Game $game, User $user, Rank $rank)
    {
        $this->rows = $rows;
        $this->game = $game;
        $this->user = $user;
        $this->rank = $rank;
    }

    private function renderItem(Match $match)
    {
        if ($match->user1Id === $this->user->id) {
            $opponent = User::findByPrimaryKey($match->user2Id);
            $userDelta = round($match->user1Delta);
        } else {
            $opponent = User::findByPrimaryKey($match->user1Id);
            $userDelta = round($match->user2Delta);
        }

        $date = $match->eventTime->format("Y-m-d H:i");
        $info = ($userDelta > 0 ? '+' . $userDelta : $userDelta) . ' points';
        $info .= ', ' . $date;

        echo (new UserPlate($opponent, $info));
    }

    public function render()
    {
        $gamePlate = new GamePlate($this->game);
        $info = 'Rank: ' . $this->rank->show();
        $userPlate = new UserPlate($this->user, $info);
        echo <<<HTML
<div class="row">
    {$userPlate}
    {$gamePlate}
</div>
<div class="row">
    <h2>History</h2>
</div>
<div class="row">
HTML;
        foreach ($this->rows as $i => $match) {
            echo $this->renderItem($match);
        }

        echo <<<HTML
</div>
HTML;

    }

}