<?php

namespace Rankster\View;

use Rankster\Entity\Game;
use Rankster\Entity\Match;
use Rankster\Entity\User;
use Rankster\Service\AuthSession;
use Rankster\Web;
use Yaoi\Command\Io;
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

    public $title;

    public $hideGameColumn = false;

    /**
     * History constructor.
     * @param Match[] $rows
     */
    public function __construct($rows, Io $io)
    {
        $this->rows = $rows;
        $this->matchDetails = Web\Match\Details::createState($io);
        $this->gameDetails = Web\Game\Details::createState($io);
        $this->userDetails = Web\User\Details::createState($io);
    }

    /** @var \Yaoi\Command\State|Web\Match\Details  */
    private $matchDetails;
    /** @var \Yaoi\Command\State|Web\Game\Details */
    private $gameDetails;
    /** @var \Yaoi\Command\State|Web\User\Details */
    private $userDetails;

    public function renderItem(Match $match)
    {
        $game = Game::findByPrimaryKey($match->gameId);
        $user1 = User::findByPrimaryKey($match->user1Id);
        $user2 = User::findByPrimaryKey($match->user2Id);

        $w1 = $user1->id === $match->winnerId ? ' class="winner"' : '';
        $w2 = $user2->id === $match->winnerId ? ' class="winner"' : '';

        $d1 = $match->user1Delta > 0 ? '+' . $match->user1Delta : $match->user1Delta;
        $d2 = $match->user2Delta > 0 ? '+' . $match->user2Delta : $match->user2Delta;

        $this->matchDetails->matchId = $match->id;
        $this->gameDetails->gameId = $game->id;
        $this->userDetails->userId = $user1->id;
        $user1Details = $this->userDetails->makeAnchor();

        $this->userDetails->userId = $user2->id;
        $user2Details = $this->userDetails->makeAnchor();

        $result = <<<HTML
    <tr>
        <td scope="row" style="width:90px"><a href="{$this->matchDetails->makeAnchor()}">{$match->eventTime->format('Y-m-d H:i')}</a></td>
        <td><a href="{$this->gameDetails->makeAnchor()}"><img class="i20" src="{$game->getFullUrl()}" title="{$game->name}" /></a></td>
        <td{$w1}><img class="i20" src="{$user1->getFullUrl()}" /> <a href="{$user1Details}">{$user1->name}</a><br /><span class="rank">{$match->user1NewRank}({$d1})</span></td>
        <td{$w2}><img class="i20" src="{$user2->getFullUrl()}" /> <a href="{$user2Details}">{$user2->name}</a><br /><span class="rank">{$match->user2NewRank}({$d2})</span></td>
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
            $rows = "<tr><td>No data</td></tr>";
        }

        echo <<<HTML
<div class="col-lg-6">
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