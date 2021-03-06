<?php

namespace Rankster\Web\MatchRequest;


use Rankster\Entity\MatchRequest as MatchRequestEntity;
use Rankster\Entity\Match as MatchEntity;
use Rankster\Entity\WhiteList;
use Rankster\Service\AuthSession;
use Rankster\View\MatchRequestList;
use Rankster\View\Pagination;
use Yaoi\Command;
use Yaoi\Command\Definition;
use Yaoi\Io\Content\Heading;

class MatchRequest extends Command
{
    public $page;
    public $pageRejected;
    public $pageOpponent;
    public $whiteListed;
    public $accept;
    public $reject;
    public $action;
    public $mrId;

    /**
     * Required setup option types in provided options object
     * @param $definition Definition
     * @param $options static|\stdClass
     */
    static function setUpDefinition(Definition $definition, $options)
    {
        $options->page = Command\Option::create()->setType();
        $options->pageOpponent = Command\Option::create()->setType();
        $options->pageRejected = Command\Option::create()->setType();
        $options->action = Command\Option::create()->setType();
        $options->whiteListed = Command\Option::create()->setType();
        $options->mrId = Command\Option::create()->setType();
        $options->accept = Command\Option::create()->setType();
        $options->reject = Command\Option::create()->setType();
    }

    public function performAction()
    {
        $user = AuthSession::getUser();
        if (!$user) {
            header('Location: /');
            return;
        }

        if ($this->action == 'process') {
            $this->processAction();
        }

        $perPage = 20;
        if (!$this->page) {
            $this->page = 1;
        }

        $commandState = self::createState($this->io);

        $confirmationList = new MatchRequestList(
            MatchRequestEntity::getMatchRequestNewList($user->id, $perPage, $this->page)
        );
        $confirmationList->title = 'Pending your confirmation';
        $confirmationList->setPagination(new Pagination(
            $commandState,
            self::options()->page,
            ceil($user->getMatchRequestNewCount() / $perPage)
        ));

        $opponentList = new MatchRequestList(
            MatchRequestEntity::getMatchRequestNewOpponentList($user->id, $perPage, $this->pageOpponent)
        );
        $opponentList->title = 'Pending opponent confirmation';
        $opponentList->isOpponent = true;
        $opponentList->setPagination(new Pagination(
            $commandState,
            self::options()->pageOpponent,
            ceil(MatchRequestEntity::getCountOpponentNew($user->id) / $perPage)
        ));

        $rejectedList = new MatchRequestList(
            MatchRequestEntity::getMatchRequestRejectedList($user->id, $perPage, $this->pageRejected)
        );
        $rejectedList->title = 'Rejected';
        $rejectedList->isOpponent = true;
        $rejectedList->setPagination(new Pagination(
            $commandState,
            self::options()->pageRejected,
            ceil(MatchRequestEntity::getCountRejected($user->id) / $perPage)
        ));

        $this->response->addContent(new Heading('Matches'));
        $this->response->addContent('<div class="row">');
        $this->response->addContent($confirmationList);
        $this->response->addContent($opponentList);
        $this->response->addContent($rejectedList);
        $this->response->addContent('</div>');
    }

    protected function processAction()
    {
        $mr = MatchRequestEntity::findByPrimaryKey($this->mrId);
        if (!$mr) {
            return false;
        }

        $user = AuthSession::getUser();
        if (!$user || $mr->user2Id !== $user->id) {
            return false;
        }

        if ($this->accept !== null && $this->whiteListed !== null) {
            return $this->addToWhiteListAndProcessAll($mr->user1Id, $mr->gameId);
        }

        $this->processMatchRequest($mr);
    }

    protected function addToWhiteListAndProcessAll($userId, $gameId)
    {
        $user = AuthSession::getUser();
        $whiteList = new WhiteList();
        $whiteList->user1Id = $user->id;
        $whiteList->user2Id = $userId;
        $whiteList->gameId = $gameId;
        $whiteList->eventTime = new \DateTime();

        $whiteList->findOrSave();

        $page = 1;
        $perPage = 50;

        do {
            $list = MatchRequestEntity::getMatchRequestNewListForUserAndGame($user->id, $userId, $gameId, $perPage, $page);
            /** @var MatchRequestEntity $mr */
            foreach ($list as $mr) {
                $this->processMatchRequest($mr);
            }
            $page++;
        } while (count($list) == $perPage);

    }

    /**
     * @param MatchRequestEntity $mr
     */
    protected function processMatchRequest($mr)
    {
        if ($this->reject !== null) {
            $mr->finalizeStatus(MatchRequestEntity::STATUS_REJECTED);
            return;
        }
        $mr->finalizeStatus(MatchRequestEntity::STATUS_ACCEPTED);

        $match = MatchEntity::make($mr->user1Id, $mr->user2Id, $mr->gameId, MatchEntity::RESULT_DRAW);
        $match->winnerId = $mr->winnerId;
        $match->applyRanks();
    }

}