<?php

namespace Rankster\Entity;

use Yaoi\Database;
use Yaoi\Database\Definition\Column;
use Yaoi\Database\Entity;

class GameGroup extends Entity
{
    public $gameId;
    public $groupId;
    public $matchesCount;
    public $playersCount;

    /**
     * @param \stdClass|static $columns
     */
    static function setUpColumns($columns)
    {
        $columns->gameId = Game::columns()->id;
        $columns->groupId = Group::columns()->id;
        $columns->playersCount = Column::create(Column::INTEGER + Column::NOT_NULL)->setDefault(0);
        $columns->matchesCount = Column::create(Column::INTEGER + Column::NOT_NULL)->setDefault(0);
    }

    /**
     * @param \Yaoi\Database\Definition\Table $table
     * @param \stdClass|static $columns
     */
    static function setUpTable(\Yaoi\Database\Definition\Table $table, $columns)
    {
        $table->setSchemaName('game_grp')->setPrimaryKey($columns->gameId, $columns->groupId);
    }

    public static function incrementPlayersCount($gameId, $groupId)
    {
        $cols = static::columns();
        Database::getInstance()
            ->query("INSERT INTO ? (?, ?, ?) VALUES (?, ?, 1) ON DUPLICATE KEY UPDATE ? = VALUES(?)",
                static::table(), $cols->gameId . $cols->groupId, $cols->playersCount,
                $gameId, $groupId,
                $cols->playersCount, $cols->playersCount
            )
            ->execute();
    }

    public static function incrementMatchesCount($gameId, $groupId)
    {
        $cols = static::columns();
        Database::getInstance()
            ->query("INSERT INTO ? (?, ?, ?) VALUES (?, ?, 1) ON DUPLICATE KEY UPDATE ? = VALUES(?)",
                static::table(), $cols->gameId, $cols->groupId, $cols->matchesCount,
                $gameId, $groupId,
                $cols->matchesCount, $cols->matchesCount
            )
            ->execute();
    }

}