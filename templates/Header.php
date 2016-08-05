<?php
namespace Rankster;

use Rankster\Entity\Match;
use Rankster\Service\AuthSession;

if ($currentUser = AuthSession::getUser()) { ?>
<div class="row">
  <div class="col-sm-6 col-lg-3" style="max-height:123px;">
    <div class="card-box widget-user" style="min-height:123px;">
      <div>
        <img src="<?= $currentUser->getFullUrl() ?>" class="img-responsive img-circle" alt="user">
        <div class="wid-u-info">
          <h4 class="m-t-0 m-b-5"><?= $currentUser->name ?></h4>
          <p class="text-muted m-b-5 font-13"><?= $currentUser->email ?></p>
          <small class="text-success"><b>Rookie</b></small>
          <a href="/logout" class="btn btn-default btn-xs" style="float:right;margin-top:15px">Logout</a>
        </div>
      </div>
    </div>
  </div>
    
    <?php
      $userStats = Match::getTotalWins($currentUser->id);
    ?>
  <div class="col-sm-6 col-lg-3">
    <div class="widget-simple-chart text-right card-box">
      <div title="Victory rate" class="circliful-chart circliful" data-dimension="90"
           data-text="<?php echo $userStats["percents"]; ?>%"
           data-width="5" data-fontsize="14" data-percent="<?php echo $userStats["percents"]; ?>"
           data-fgcolor="#5fbeaa" data-bgcolor="#ebeff2" style="width: 90px;">
      </div>
      <h3 class="text-success counter"><?php echo $userStats["total"]; ?></h3>
      <p class="text-muted">Total Games This Week</p>
    </div>
  </div>

  <div class="col-sm-6 col-lg-3">
  <div class="widget-simple-chart text-right card-box" style="min-height: 123px;text-align: center;">
    <button class="btn btn-default waves-effect waves-light btn-sm" id="sa-win" style="display:none;">Click me</button>
    <button class="btn btn-default waves-effect waves-light btn-sm" id="sa-draw" style="display:none;">Click me</button>
    <button class="btn btn-default waves-effect waves-light btn-sm" id="sa-lose" style="display:none;">Click me</button>
    <span class="btn btn-lg btn-danger waves-effect waves-light m-b-5" style="width:177px;margin:18px">
      <a href="#game-req" rel="modal:open">
        <i class="glyphicon glyphicon-new-window m-r-5" style="color: #fff;"></i>
        <span style="color: #fff;">New Match</span>
      </a>
    </span>
    </div>
  </div>

  <div class="col-sm-6 col-lg-3">
    <div class="widget-simple-chart text-right card-box" style="min-height: 123px;text-align: center;">
      <?php
      $rematch = $currentUser->findLastMatch();
      if ($rematch) {
        /** @var \Rankster\Entity\User $user */
        $user = $rematch['user'];
        $userJson = json_encode($user);
        /** @var \Rankster\Entity\Game $game */
        $game = $rematch['game'];
        $gameJson = json_encode($game);

        ?>
        <span title="Submit score" class="btn btn-xs btn-danger waves-effect waves-light m-b-5"
              onclick='gameReplayDialog(<?=$gameJson?>, <?=$userJson?>)'>
            <i style="color: #fff;" class="glyphicon glyphicon-new-window m-r-5"></i> Play again
        </span>

        <img src="http://collegiateattire.com/wp-content/uploads/2016/06/versus-icon-vs-icon-315x400.jpg" width="50"/>
        <img class="img-circle" src="<?=$user->getFullUrl()?>">
        <img class="img-circle" width="55" src="<?=$game->getFullUrl()?>">
        <?php
      }
      ?>

    </div>
  </div>

</div>

<?php } else { ?>
<div class="row">
  <div class="col-sm-6 col-lg-3">
    <div class="card-box">
      <div>
        <a href="/login" class="btn btn-block btn-social btn-facebook">
          <span class="fa fa-facebook"></span> Sign in with Facebook
        </a>
      </div>
    </div>
  </div>
</div>
<?php } ?>
