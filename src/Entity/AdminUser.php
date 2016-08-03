<?php

namespace Rankster\Entity;

use Yaoi\Database\Entity;

class AdminUser extends Entity
{
    public $userId;

    /**
     * @param \stdClass|static $columns
     */
    static function setUpColumns($columns)
    {
        $columns->userId = User::columns()->id;
    }

    /**
     * @param \Yaoi\Database\Definition\Table $table
     * @param \stdClass|static $columns
     */
    static function setUpTable(\Yaoi\Database\Definition\Table $table, $columns)
    {
        $table->setPrimaryKey($columns->userId);
    }


}