<?php

namespace Rankster\View;


use Rankster\Entity\Game;
use Rankster\Entity\Rank;
use Rankster\Entity\RankHistory;
use Rankster\Entity\User;
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
            $title = $game->name . '<br/>' . $r->matches . 'matches played';

        } else {
            $image = \Rankster\Entity\User::patchToUrl($rank['picture_path']);
            $title = $user->name . '<br/>' . $r->matches . 'matches played';
        }

        $history = RankHistory::statement()->where('? = ? AND ? = ?',
            RankHistory::columns()->userId, $user->id,
            RankHistory::columns()->gameId, $r->gameId)
            ->order('? ASC', RankHistory::columns()->id)->bindResultClass()
            ->query()
            ->fetchAll(null, 'rank');
        $history = implode(',', $history);


        $gameJson = json_encode($game->toArray());
        $userJson = json_encode($user->toArray());
        return <<<HTML
    <tr>
        <th scope="row">{$firstcol}</th>
        <td><img class="img-circle" style="width:50px;height:50px" src="{$image}"/></td>
        <td>{$title}</td>
        <td>{$r->rank}</td>
        <td style="width:80px">
            <div id="r{$rank['id']}-{$r->gameId}" style="width:80px;height: 20px"></div>
            <script>
                $("#r{$rank['id']}-{$r->gameId}").sparkline([{$history}], {
                    type: 'line',
                    width: '80px',
                    height: '20px'
                });
            </script>
        </td>
        <td>
        <span title="Submit score" class="btn btn-xs btn-danger waves-effect waves-light m-b-5" onclick='gameReplayDialog($gameJson, $userJson)'>
            <i style="color: #fff;" class="glyphicon glyphicon-new-window m-r-5"></i>
        </span>
        </td>
    </tr>
HTML;
}

    public function render()
    {

        $rows = '';
        foreach ($this->userRanks as $i => $rank) {
            $rows .= $this->renderItem($i, $rank);
        }

        echo <<<HTML
<div class="col-lg-6">
    <div class="card-box">
        <h4 class="m-t-0 header-title" style="float: right"><b>{$this->name}</b></h4>
        <p class="text-muted font-13 m-b-25">
            <img class="img-circle" width="75" src="{$this->image}">
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