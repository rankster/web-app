<?php

namespace Rankster\Entity;

use Yaoi\Database\Definition\Column;
use Yaoi\Database\Definition\Index;
use Yaoi\Database\Definition\Table;
use Yaoi\Database\Entity;
use Yaoi\Undefined;

class MatchRequest extends Entity
{
    const STATUS_NEW = 'new';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_REJECTED = 'rejected';

    public $id;
    public $gameId;
    public $user1Id;
    public $user2Id;
    public $winnerId;
    public $status;
    public $eventTime;
    public $actionTime;

    /**
     * Required setup column types in provided columns object
     * @param $columns static|\stdClass
     */
    static function setUpColumns($columns)
    {
        $columns->id = Column::AUTO_ID;
        $columns->gameId = Game::columns()->id;
        $columns->user1Id = User::columns()->id;
        $columns->user2Id = User::columns()->id;
        $columns->winnerId = User::columns()->id;
        $columns->status = Column::STRING;
        $columns->eventTime = Column::INTEGER + Column::USE_PHP_DATETIME;
        $columns->actionTime = Column::INTEGER + Column::USE_PHP_DATETIME;
    }

    /**
     * Optional setup table indexes and other properties, can be left empty
     * @param Table $table
     * @param static|\stdClass $columns
     * @return void
     */
    static function setUpTable(\Yaoi\Database\Definition\Table $table, $columns)
    {
        Column::cast($columns->winnerId)->setFlag(Column::NOT_NULL, false);
        Column::cast($columns->status)->setDefault(self::STATUS_NEW);
        $table->setSchemaName('match_request')
            ->addIndex(Index::TYPE_KEY, $columns->user2Id, $columns->status);
    }

    public static function make($user1Id, $user2Id, $gameId, $result)
    {
        $match = new static();
        $match->user1Id = $user1Id;
        $match->user2Id = $user2Id;
        $match->gameId = $gameId;
        $match->eventTime = new \DateTime();

        if ($result === Match::RESULT_DRAW) {
            $match->winnerId = null;
        } elseif ($result === Match::RESULT_WIN) {
            $match->winnerId = $match->user1Id;
        } else {
            $match->winnerId = $match->user2Id;
        }

        return $match;
    }

    public static function getCountNew($userId)
    {
        $query = MatchRequest::statement()->select('count(*) as cnt')
            ->where('? = ?', MatchRequest::columns()->user2Id, $userId)
            ->where('? = ?', MatchRequest::columns()->status, MatchRequest::STATUS_NEW)
            ->query();
        $query->bindResultClass();
        $cnt = $query->fetchRow();

        return $cnt['cnt'];
    }

    public static function getMatchRequestNewList($userId, $perPage = 20, $page = 1)
    {
        return MatchRequest::statement()
            ->where('? = ?', MatchRequest::columns()->user2Id, $userId)
            ->where('? = ?', MatchRequest::columns()->status, MatchRequest::STATUS_NEW)
            ->order('? DESC', MatchRequest::columns()->eventTime)
            ->limit($perPage, $perPage * ($page - 1))
            ->query()
            ->fetchAll();
    }

    public static function getMatchRequestNewListForUserAndGame($userId, $userOpponent, $gameId, $perPage = 20, $page = 1)
    {
        return MatchRequest::statement()
            ->where('? = ?', MatchRequest::columns()->user1Id, $userOpponent)
            ->where('? = ?', MatchRequest::columns()->user2Id, $userId)
            ->where('? = ?', MatchRequest::columns()->status, MatchRequest::STATUS_NEW)
            ->where('? = ?', MatchRequest::columns()->gameId, $gameId)
            ->order('? ASC', MatchRequest::columns()->eventTime)
            ->limit($perPage, $perPage * ($page - 1))
            ->query()
            ->fetchAll();
    }

    public function finalizeStatus($status)
    {
        $this->status = $status;
        $this->eventTime = new \DateTime();
        $this->save();
        return $this;
    }
}
