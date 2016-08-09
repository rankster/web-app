<?php

namespace Rankster\View;

use Rankster\Entity\Game;
use Rankster\Entity\Match;
use Rankster\Entity\User;
use Rankster\Service\AuthSession;
use Yaoi\View\Hardcoded;

class History extends Hardcoded
{
    private $pagination;
    public function setPagination(Pagination $pagination)
    {
        $this->pagination = $pagination;
        return $this;
    }

    /** @var \Rankster\Entity\Match[]  */
    private $rows;

    /**
     * History constructor.
     * @param Match[] $rows
     */
    public function __construct($rows)
    {
        $this->rows = $rows;
    }

    public function renderItem(Match $match)
    {

        $game = Game::findByPrimaryKey($match->gameId);
        $user1 = User::findByPrimaryKey($match->user1Id);
        $user2 = User::findByPrimaryKey($match->user2Id);

        $result = <<<HTML
    <tr>
        <th scope="row">{$match->eventTime->format('Y-m-d H:i')}</th>
        <td>{$game->name}</td>
        <td>{$user1->name} {$match->user1Delta}</td>
        <td>{$user2->name} {$match->user2Delta}</td>
    </tr>
HTML;
        return $result;

    }

    private $currentUserId;

    public function render()
    {
        $this->currentUserId = AuthSession::getUserId();

        // date | game | user1 * r1 d1 p1 | user2 r2 d2 p2 |

        $rows = '';
        foreach ($this->rows as $i => $match) {
            $rows .= $this->renderItem($match);
        }

        echo <<<HTML
<div class="col-lg-6">
    <div class="card-box">
        <table class="table m-0">
            <tbody>
            {$rows}
            </tbody>
        </table>
    </div>
{$this->pagination}
</div>

HTML;

    }


}