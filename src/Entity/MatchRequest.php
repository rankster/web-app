<?php

namespace Rankster\Entity;

use Yaoi\Database\Definition\Column;
use Yaoi\Database\Definition\Index;
use Yaoi\Database\Definition\Table;
use Yaoi\Database\Entity;
use Yaoi\Undefined;

class MatchRequest extends Entity
{
    public $id;
    public $gameId;
    public $user1Id;
    public $user2Id;
    public $winnerId;
    public $eventTime;

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
        $columns->eventTime = Column::INTEGER + Column::USE_PHP_DATETIME;
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
        $table->setSchemaName('match_request');
    }

    public static function make($user1Id, $user2Id, $gameId, $result)
    {
        $match = new Match();
        $match->user1Id = $user1Id;
        $match->user2Id = $user2Id;
        $match->gameId = $gameId;
        $match->eventTime = new \DateTime();

        $match->status = Match::STATUS_ACCEPT;
        if ($result === Match::RESULT_DRAW) {
            $match->winnerId = null;
        } elseif ($result === Match::RESULT_WIN) {
            $match->winnerId = $match->user1Id;
        } else {
            $match->winnerId = $match->user2Id;
        }

        return $match;
    }
}
