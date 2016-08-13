<?php

namespace Rankster\Entity;

use Yaoi\Database\Definition\Column;
use Yaoi\Database\Definition\Index;
use Yaoi\Database\Definition\Table;
use Yaoi\Database\Entity;
use Yaoi\Undefined;

class WhiteList extends Entity
{
    public $id;
    public $gameId;
    public $user1Id;
    public $user2Id;
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
        $table->setSchemaName('white_list')
            ->addIndex(Index::TYPE_UNIQUE, $columns->gameId, $columns->user1Id, $columns->user2Id);
    }

    static public function isWhiteListed($userId, $opponentId, $gameId)
    {
        $wl = new static();
        $wl->gameId = $gameId;
        $wl->user1Id = $opponentId;
        $wl->user2Id = $userId;

        return $wl->findSaved();
    }
}
