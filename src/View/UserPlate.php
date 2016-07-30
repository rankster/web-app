<?php

namespace Rankster\View;


use Rankster\Entity\User;
use Yaoi\View\Hardcoded;

class UserPlate extends Hardcoded
{
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public $user;

    public function render()
    {
        $user = $this->user;
        echo <<<HTML
  <div class="col-sm-6 col-lg-3" style="max-height:123px;">
    <div class="card-box widget-user" style="min-height:123px;">
      <div>
        <img src="http://rankster.penix.tk/user-images{$user->picturePath}" class="img-responsive img-circle" alt="user">
        <div class="wid-u-info">
          <h4 class="m-t-0 m-b-5">{$user->name}</h4>
          
          <small class="text-success"><b>Rookie</b></small>
        </div>
      </div>
    </div>
  </div>

HTML;

    }


}