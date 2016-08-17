<?php

namespace Rankster\View;

use Rankster\Entity\Game;
use Rankster\Entity\MatchRequest as MatchRequestEntity;
use Rankster\Entity\User;
use Rankster\Service\AuthSession;
use Rankster\Web\MatchRequest\MatchRequest;
use Yaoi\View\Hardcoded;

class MatchRequestList extends Hardcoded
{
    private $pagination;
    public function setPagination(Pagination $pagination)
    {
        $this->pagination = $pagination;
        return $this;
    }

    /** @var MatchRequestEntity[] */
    private $rows;

    public $title;

    public $hideGameColumn = false;

    public $isOpponent = false;

    /**
     * History constructor.
     * @param MatchRequest[] $rows
     */
    public function __construct($rows)
    {
        $this->rows = $rows;
    }

    public function renderItem(MatchRequestEntity $matchRequest)
    {

        $game = Game::findByPrimaryKey($matchRequest->gameId);
        $user1 = User::findByPrimaryKey($matchRequest->user1Id);
        $user2 = User::findByPrimaryKey($matchRequest->user2Id);

        if ($user1->id === $matchRequest->winnerId) {
            $w = ' class="winner"';
            $status = ' wins ';
        } else {
            $w = ' class="loser"';
            $status = ' loses to ';
        }

        $result = <<<HTML
    <tr>
        <td scope="row" style="width:90px">{$matchRequest->eventTime->format('Y-m-d H:i')}</td>
        <td><a href="/game/details/?game_id={$game->id}"><img class="i20" src="{$game->getFullUrl()}" title="{$game->name}" /></a></td>
        <td{$w}><img class="i20" src="{$user1->getFullUrl()}" /> <a href="/user/details/?user_id={$user1->id}">{$user1->name}</a> {$status}
        <img class="i20" src="{$user2->getFullUrl()}" /> <a href="/user/details/?user_id={$user2->id}">{$user2->name}</a></td>

HTML;
        if (!$this->isOpponent) {
            $result .= <<<HTML
        <td>
            <form method="post" action="/match-request">
                <input type="hidden" name="action" value="process">
                <input type="hidden" name="mr_id" value="{$matchRequest->id}">
                
                <button name="accept" type="submit" class="btn btn-success confirm-button" autocomplete="off">Confirm</button>        
                <button name="reject" type="submit" class="btn btn-danger reject-button" autocomplete="off">Reject</button>
                <p>
                    <label>
                        <input type="checkbox" name="white_listed"> Auto confirm
                    </label>
                </p>
            </form>
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

        // date | game | user1 * r1 d1 p1 | user2 r2 d2 p2 |

        $rows = '';
        foreach ($this->rows as $i => $match) {
            $rows .= $this->renderItem($match);
        }

        if (!$rows) {
            $rows = '<tr><td>No data</td></tr>';
        }

        $result = <<<HTML
<div class="col-lg-6">
    <div class="card-box">
        <h4 class="m-t-0 header-title" style="float: right"><b>{$this->title}</b></h4>

        <table class="table m-0">
            <tbody>
            {$rows}
            </tbody>
        </table>

HTML;

        if (!$this->isOpponent && $this->rows) {
            $result .= <<<HTML
        <div class="well">"Auto confirm" is a sign of trust, if checked all matches (including pending confirmation) 
        from this user and this game will be confirmed automatically</div>
HTML;
        }

        $result .= <<<HTML

    </div>
{$this->pagination}
</div>

HTML;

        echo $result;

    }


}