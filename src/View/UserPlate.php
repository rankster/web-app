<?php

namespace Rankster\View;


use Rankster\Entity\User;
use Yaoi\View\Hardcoded;

class UserPlate extends Hardcoded
{
    public function __construct(User $user, $rank = null)
    {
        $this->user = $user;
        $this->rank = $rank;
    }

    public $user;
    public $rank;

    public function render()
    {
        $user = $this->user;
        echo <<<HTML
  <div class="col-sm-6 col-lg-3">
    <div class="card-box widget-user">
      <div>
        <img src="http://rankster.penix.tk/user-images{$user->picturePath}" class="img-responsive img-circle" alt="user">
        <div class="wid-u-info">
          <h4 class="m-t-0 m-b-5">{$user->name}</h4>
          <p class="text-muted m-b-5 font-13">{$this->rank}</p>          
          <small class="text-success"><b>Rookie</b></small>
        </div>
      </div>
    </div>
  </div>

HTML;

    }


}