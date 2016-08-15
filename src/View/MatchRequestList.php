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
            $w = ' class="looser"';
            $status = ' loses ';
        }

        $result = <<<HTML
    <tr>
        <td scope="row" style="width:90px">{$matchRequest->eventTime->format('Y-m-d H:i')}</td>
        <td><a href="/game/details/?game_id={$game->id}"><img class="i20" src="{$game->getFullUrl()}" title="{$game->name}" /></a></td>
        <td{$w}><img class="i20" src="{$user1->getFullUrl()}" /> <a href="/user/details/?user_id={$user1->id}">{$user1->name}</a> {$status}
        <img class="i20" src="{$user2->getFullUrl()}" /> <a href="/user/details/?user_id={$user2->id}">{$user2->name}</a></td>
        <td>
            <form method="post" action="/match-request">
                <input type="hidden" name="action" value="process">
                <input type="hidden" name="mr_id" value="{$matchRequest->id}">
                
                <button name="accept" type="submit" class="btn btn-success confirm-button" autocomplete="off">Confirm</button>        
                <button name="reject" type="submit" class="btn btn-danger reject-button" autocomplete="off">Reject</button>
                <p>
                    <label title="this action will process all requests from this user">
                        <input type="checkbox" name="white_listed"> Add <u>{$user1->name}</u> to white list
                    </label>
                </p>
            </form>
        </td>
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
<div>
    <div class="card-box">
        <h4 class="m-t-0 header-title" style="float: right"><b>{$this->title}</b></h4>

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