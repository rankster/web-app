<?php

namespace Rankster\View;


use Rankster\Entity\Game;
use Yaoi\View\Hardcoded;

class GamePlate extends Hardcoded
{
    public function __construct(Game $game)
    {
        $this->game = $game;
    }

    public $game;

    public function render()
    {
        $game = $this->game;
        $image = $game->getFullUrl();


        echo <<<HTML
  <div class="col-sm-4 col-lg-3">
    <div class="card-box widget-user">
      <div>
        <img src="{$image}" class="img-responsive" alt="user">
        <div class="wid-u-info">
          <h4 class="m-t-0 m-b-5"><a href="/game/details/?game_id={$game->id}">{$game->name}</a></h4>
          <p class="text-muted m-b-5 font-13">Players: {$game->playersCount}, matches: {$game->matchesCount}</p>          
        </div>
      </div>
    </div>
  </div>

HTML;
    }


}