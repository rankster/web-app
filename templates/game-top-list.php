<?php
namespace Rankster;

use Rankster\Entity\Rank;
use Rankster\Entity\RankHistory;
use Rankster\Entity\User;

$game = new \Rankster\Entity\Game();
$game->id = $GLOBALS['gameId'];
$game = $game->findSaved();
?>
<div class="col-lg-6">
    <div class="card-box">
        <h4 class="m-t-0 header-title" style="float: right"><b><?php echo $game->name ?></b></h4>
        <p class="text-muted font-13 m-b-25">
            <img class="img-circle" width="75" src="<?php echo $game->getFullUrl(); ?>">
        </p>

        <table class="table m-0">
            <tbody>
            <?php foreach (\Rankster\Entity\Rank::getRanks($game->id) as $i => $rank) {
                $history = RankHistory::statement()->where('? = ? AND ? = ?',
                    RankHistory::columns()->userId, $rank['id'],
                    RankHistory::columns()->gameId, $game->id)
                    ->order('? ASC', RankHistory::columns()->id)->bindResultClass()
                    ->query()
                    ->fetchAll(null, 'rank');

                $user = User::fromArray($rank);
                $r = Rank::fromArray($rank);

                ?>
                <tr>
                    <th scope="row">
                        <?php if ($i == 0): ?>
                            <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
                        <?php else: ?>
                            <?php echo($i + 1); ?>
                        <?php endif; ?>
                    </th>
                    <td><img class="img-circle"
                             src="<?php echo \Rankster\Entity\User::patchToUrl($rank['picture_path']); ?>"/></td>
                    <td><?php echo $user->name; ?><br /><?php echo $r->matches ?> matches played</td>
                    <td><?php echo $r->rank; ?></td>
                    <td style="width:80px">
                        <div id="r<?= $rank['id'] . '-' . $game->id ?>" style="width:80px;height: 20px"></div>
                        <script>
                            $("#r<?=$rank['id'] . '-' . $game->id ?>").sparkline([<?php echo implode(',', $history) ?>], {
                                type: 'line',
                                width: '80px',
                                height: '20px'
                            });
                        </script>
                    </td>
                    <td>
                        <button onclick='gameReplayDialog(<?=json_encode($game->toArray())?>, <?=json_encode($user->toArray())?>)'>...</button>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>
