<?php

namespace Rankster\View;


use Rankster\Entity\RankHistory;
use Rankster\Entity\User;
use Yaoi\View\Hardcoded;

class UserRankTable extends Hardcoded
{

    public $name;
    public $image;

    public $gameId;
    /** @var array */
    public $userRanks;

    protected function renderItem($i, $rank)
    {
        if ($i == 0) {
            $firstcol = '<span class="glyphicon glyphicon-star" aria-hidden="true"></span>';
        } else {
            $firstcol = $i + 1;
        }

        $image = \Rankster\Entity\User::patchToUrl($rank['picture_path']);

        $user = User::fromArray($rank);
        $r = RankHistory::fromArray($rank);

        $history = RankHistory::statement()->where('? = ? AND ? = ?',
            RankHistory::columns()->userId, $user->id,
            RankHistory::columns()->gameId, $r->gameId)
            ->order('? ASC', RankHistory::columns()->id)->bindResultClass()
            ->query()
            ->fetchAll(null, 'rank');
        $history = implode(',', $history);



        return <<<HTML
    <tr>
        <th scope="row">{$firstcol}</th>
        <td><img class="img-circle" src="{$image}"/></td>
        <td>{$user->name}<br/>{$r->matches} matches played</td>
        <td><?php echo $r->rank; ?></td>
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