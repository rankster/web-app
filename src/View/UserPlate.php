<?php

namespace Rankster\View;


use Rankster\Entity\User;
use Yaoi\View\Hardcoded;

class UserPlate extends Hardcoded
{
    public function __construct(User $user, $info = null)
    {
        $this->user = $user;
        $this->info = $info;
    }

    public $user;
    public $info;

    public function render()
    {
        $user = $this->user;
        echo <<<HTML
  <div class="col-sm-4 col-lg-3">
    <div class="card-box widget-user">
      <div>
        <img src="{$user->getFullUrl()}" class="img-responsive img-circle" alt="user">
        <div class="wid-u-info">
          <h4 class="m-t-0 m-b-5">{$user->name}</h4>
          <p class="text-muted m-b-5 font-13">{$this->info}</p>          
          <small class="text-success"><b>Rookie</b></small>
        </div>
      </div>
    </div>
  </div>

HTML;

    }


}