<?php

namespace Rankster\View\Match;


use Rankster\Entity\Game;
use Rankster\Entity\Match;
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

    public function __construct($rows, Game $game, User $user)
    {
        $this->rows = $rows;
        $this->game = $game;
        $this->user = $user;
    }

    private function renderItem(Match $match)
    {
        if ($match->user1Id === $this->user->id) {
            $opponent = User::findByPrimaryKey($match->user2Id);
            $opponentDelta = $match->user2Delta;
        } else {
            $opponent = User::findByPrimaryKey($match->user1Id);
            $opponentDelta = $match->user1Delta;
        }

        echo (new UserPlate($opponent, ($opponentDelta > 0 ? '+' . $opponentDelta : $opponentDelta) . ' points'));
    }

    public function render()
    {
        $gamePlate = GamePlate::create($this->game);
        $userPlate = UserPlate::create($this->user);
        echo <<<HTML
<div class="row">
    {$userPlate}
    {$gamePlate}
</div>
HTML;


        foreach ($this->rows as $i => $match) {
            echo $this->renderItem($match);
        }
    }

}