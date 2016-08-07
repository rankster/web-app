<?php

namespace Rankster\View;


use Rankster\Entity\Game;
use Rankster\Entity\Rank;
use Rankster\Entity\RankHistory;
use Rankster\Entity\User;
use Rankster\Service\AuthSession;
use Rankster\View\SubmitScore\Data;
use Yaoi\View\Hardcoded;

class UserRankTable extends Hardcoded
{
    public $byUser = true;

    public $name;
    public $image;

    /** @var array */
    public $userRanks;

    protected function renderItem($i, $rank)
    {
        if ($i == 0) {
            $firstcol = '<span class="glyphicon glyphicon-star" aria-hidden="true"></span>';
        } else {
            $firstcol = $i + 1;
        }

        $user = User::fromArray($rank);
        $r = Rank::fromArray($rank);
        $game = Game::findByPrimaryKey($r->gameId);

        if ($this->byUser) {
            $image = $game->getFullUrl();
            $title = <<<HTML
<a href="/game/details/?game_id={$r->gameId}">{$game->name}</a><br/>
<a href="/user/match-history?user_id={$user->id}&game_id={$r->gameId}">{$r->matches} matches</a> played

HTML;

        } else {
            $image = \Rankster\Entity\User::pathToUrl($rank['picture_path']);
            $title = <<<HTML
<a href="/user/details/?user_id={$user->id}">{$user->name}</a><br/>
<a href="/user/match-history?user_id={$user->id}&game_id={$r->gameId}">{$r->matches} matches</a> played

HTML;
        }

        $history = RankHistory::statement()->where('? = ? AND ? = ?',
            RankHistory::columns()->userId, $user->id,
            RankHistory::columns()->gameId, $r->gameId)
            ->order('? ASC', RankHistory::columns()->id)->bindResultClass()
            ->query()
            ->fetchAll(null, 'rank');
        $history = implode(',', $history);


        Data::getInstance()->addUserInfo($user)->addGameInfo($game);

        $result = <<<HTML
    <tr>
        <th scope="row">{$firstcol}</th>
        <td><img class="img-circle" style="width:50px;height:50px" src="{$image}"/></td>
        <td>{$title}</td>
        <td style="width:80px">
            {$r->show()}
            <div id="r{$rank['id']}-{$r->gameId}" style="width:80px;height: 20px"></div>
            <script>
                $("#r{$rank['id']}-{$r->gameId}").sparkline([{$history}], {
                    type: 'line',
                    width: '80px',
                    height: '20px'
                });
            </script>
        </td>
        
HTML;

        if ($this->currentUserId && $this->currentUserId != $user->id) {
            $result .= <<<HTML
        <td>
        <button title="Submit score" class="btn btn-xs btn-danger waves-effect waves-light m-b-5" 
                onclick='Rankster.gameReplayDialog({$game->id}, {$user->id})'>
            <i style="color: #fff;" class="glyphicon glyphicon-new-window m-r-5"></i>
            <span class="caption hidden-xs">New match</span>
        </button>
        </td>
HTML;
        }

$result .= <<<HTML
    </tr>
HTML;
        return $result;
    }


    private $currentUserId;

    public function render()
    {
        $this->currentUserId = AuthSession::getUserId();

        $rows = '';
        foreach ($this->userRanks as $i => $rank) {
            $rows .= $this->renderItem($i, $rank);
        }

        echo <<<HTML
<div class="col-lg-6">
    <div class="card-box">
        <h4 class="m-t-0 header-title" style="float: right"><b>{$this->name}</b></h4>
        <p class="text-muted font-13 m-b-25">
            <img class="img-circle" width="55" src="{$this->image}">
        </p>

        <table class="table m-0">
            <tbody>
            {$rows}
            </tbody>
        </table>
    </div>
</div>

HTML;
    }


}
