<?php

namespace Rankster\Entity;


use Yaoi\Database;
use Yaoi\Database\Definition\Table;
use Yaoi\Database\Entity;

class UserGroup extends Entity
{
    public $userId;
    public $groupId;

    /**
     * @param \stdClass|static $columns
     */
    static function setUpColumns($columns)
    {
        $columns->userId = User::columns()->id;
        $columns->groupId = Group::columns()->id;
    }

    /**
     * @param Table $table
     * @param \stdClass|static $columns
     */
    static function setUpTable(\Yaoi\Database\Definition\Table $table, $columns)
    {
        $table
            ->setSchemaName('user_grp')
            ->setPrimaryKey($columns->userId, $columns->groupId);
    }

    public static function getCommonGroups($user1Id, $user2Id)
    {
        $t2 = self::table('t2');
        $c1 = self::columns();
        $c2 = self::columns($t2);
        static::statement()
            ->innerJoin('? ON ? = ? AND ? = ?', $t2, $c1->groupId, $c2->groupId, $c2->userId, $user2Id)
            ->where('? = ?', $c1->userId, $user1Id)
            ->query()
            ->fetchAll();
    }
}