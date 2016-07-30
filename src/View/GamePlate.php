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
        $players = $game->countPlayers();

        echo <<<HTML
  <div class="col-sm-6 col-lg-3">
    <div class="card-box widget-user">
      <div>
        <img src="{$image}" class="img-responsive img-circle" alt="user">
        <div class="wid-u-info">
          <h4 class="m-t-0 m-b-5">{$game->name}</h4>
          <p class="text-muted m-b-5 font-13">Players: {$players}</p>          
          <small class="text-success"><b>Rookie</b></small>
        </div>
      </div>
    </div>
  </div>

HTML;
    }


}