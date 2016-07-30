<?php

namespace Rankster\Entity;


use Yaoi\Database\Definition\Column;
use Yaoi\Database\Definition\Table;
use Yaoi\Database\Entity;

class RankHistory extends Entity
{
    public $id;
    public $gameId;
    public $userId;
    public $rank;
    public $time;


    /**
     * @param \stdClass|static $columns
     */
    static function setUpColumns($columns)
    {
        $columns->id = Column::AUTO_ID;
        $columns->gameId = Game::columns()->id;
        $columns->userId = User::columns()->id;
        $columns->rank = Column::INTEGER;
        $columns->time = Column::INTEGER;
    }

    /**
     * @param Table $table
     * @param \stdClass|static $columns
     */
    static function setUpTable(\Yaoi\Database\Definition\Table $table, $columns)
    {
        $table->setSchemaName('rank_history');
    }


}