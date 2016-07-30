<?php

namespace Rankster\Entity;

use Yaoi\Database\Definition\Column;
use Yaoi\Database\Definition\Table;
use Yaoi\Database\Entity;

class Match extends Entity
{
    const STATUS_ACCEPT = 'accepted';
    const STATUS_REJECT = 'rejected';

    public $id;
    public $gameId;
    public $user1Id;
    public $user2Id;
    public $winnerId;
    public $eventTime;
    public $status;
    /**
     * @param \stdClass|static $columns
     */
    static function setUpColumns($columns)
    {
        $columns->id = Column::AUTO_ID;
        $columns->gameId = Game::columns()->id;
        $columns->user1Id = User::columns()->id;
        $columns->user2Id = User::columns()->id;
        $columns->winnerId = User::columns()->id;
        $columns->status = Column::STRING;
        $columns->eventTime = Column::INTEGER;
    }

    /**
     * @param Table $table
     * @param \stdClass|static $columns
     */
    static function setUpTable(\Yaoi\Database\Definition\Table $table, $columns)
    {
        Column::cast($columns->winnerId)->setFlag(Column::NOT_NULL, false);
        $table->setSchemaName('match');
    }
}
