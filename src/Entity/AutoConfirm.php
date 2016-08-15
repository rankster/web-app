<?php

namespace Rankster\Entity;

use Yaoi\Database\Definition\Table;
use Yaoi\Database\Entity;

class AutoConfirm extends Entity
{
    public $userId;
    public $opponentId;
    public $gameId;


    /**
     * @param \stdClass|static $columns
     */
    static function setUpColumns($columns)
    {
        $columns->userId = User::columns()->id;
        $columns->opponentId = User::columns()->id;
        $columns->gameId = Game::columns()->id;
    }

    /**
     * @param Table $table
     * @param \stdClass|static $columns
     */
    static function setUpTable(\Yaoi\Database\Definition\Table $table, $columns)
    {
        $table->schemaName = 'auto_confirm';
        $table->setPrimaryKey($columns->userId, $columns->opponentId, $columns->gameId);
    }


}