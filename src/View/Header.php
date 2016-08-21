<?php
namespace Rankster\View;

use Rankster\Service\Google;
use Rankster\View\Match\ReplayForm;
use Rankster\View\Match\RequestForm;
use Yaoi\View\Hardcoded;
use Rankster\Entity\Match;
use Rankster\Service\AuthSession;
use Rankster\View\SubmitScore\Data;


class Header extends Hardcoded
{

    public function render()
    {
        if ($currentUser = AuthSession::getUser()) { ?>
            <div class="row">
                <div class="col-sm-6 col-lg-3" style="max-height:123px;">
                    <div class="card-box widget-user" style="min-height:123px;">
                        <div>
                            <img src="<?= $currentUser->getFullUrl() ?>" class="img-responsive img-circle" alt="user">
                            <div class="wid-u-info">
                                <h4 class="m-t-0 m-b-5"><?= $currentUser->name ?></h4>
                                <!--p class="text-muted m-b-5 font-13"><?= $currentUser->email ?></p-->
                                <p class="text-muted m-b-5 font-13"><a href="/match-request">Pending your confirmation
                                        <span class="badge"><?= $currentUser->getMatchRequestNewCount() ?></span></a>
                                </p>
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
                        <p class="text-muted"><a href="/user/match-history?user_id=<?= $currentUser->id ?>">Matches
                            Last 7 Days</a></p>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <div class="widget-simple-chart text-right card-box"
                         style="min-height: 123px;text-align: center;padding-top:40px">
                        <button title="Submit score" class="btn btn-lg btn-danger waves-effect waves-light m-b-5"
                                onclick='Rankster.newGameDialog()'>
                            <i style="color: #fff;" class="glyphicon glyphicon-new-window m-r-5"></i>
                            <span class="caption">New match</span>
                        </button>
                    </div>
                </div>

                <?php
                $rematch = $currentUser->findLastMatch();
                if ($rematch) {
                    /** @var \Rankster\Entity\User $user */
                    $user = $rematch['user'];
                    /** @var \Rankster\Entity\Game $game */
                    $game = $rematch['game'];

                    Data::getInstance()->addUserInfo($user);
                    Data::getInstance()->addGameInfo($game);

                    ?>
                    <div class="col-sm-6 col-lg-3">
                        <div class="widget-simple-chart text-right card-box"
                             style="min-height: 123px;text-align: center;">
                            <img class="img-circle" width="50px" src="<?= $game->getFullUrl() ?>">

                            <span title="Submit score" class="btn btn-xs btn-danger waves-effect waves-light m-b-5"
                                  onclick='Rankster.gameReplayDialog(<?= $game->id ?>, <?= $user->id ?>)'>
            <i style="color: #fff;" class="glyphicon glyphicon-new-window m-r-5"></i> Play again
        </span>

                            <img
                                src="/images/versus-icon-vs-icon-315x400.jpg"
                                width="50"/>
                            <img class="img-circle" width="50px" src="<?= $user->getFullUrl() ?>">
                        </div>
                    </div>

                    <?php
                }
                ?>

            </div>

            <?php
            ReplayForm::create()->render();
            RequestForm::create()->render();
        } else {

            $google = Google::getInstance();
            echo <<<HTML
<div class="row">
                <div class="col-sm-6 col-lg-3">
                    <div class="card-box">
                        <div>
                            <a href="/login" class="btn btn-block btn-social btn-facebook">
                                <span class="fa fa-facebook"></span> Sign in with Facebook
                            </a>
                            <a href="{$google->createAuthUrl()}" class="btn btn-block btn-social btn-google">
                                <span class="fa fa-google"></span> Sign in with Google
                            </a>
                        </div>
                    </div>
                </div>
            </div>
HTML;
        }
    }
}