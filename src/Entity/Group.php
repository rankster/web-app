<?php

namespace Rankster\Entity;

use Yaoi\Database;
use Yaoi\Database\Definition\Column;
use Yaoi\Database\Entity;
use Yaoi\Migration\ClosureMigration;

class Group extends Entity
{
    public $id;
    public $ownerId;
    public $name;
    public $title;
    public $membersCount;
    public $emailPostfix;

    /**
     * @param \stdClass|static $columns
     */
    static function setUpColumns($columns)
    {
        $columns->id = Column::AUTO_ID;
        $columns->ownerId = User::columns()->id;
        $columns->name = Column::create(Column::STRING + Column::NOT_NULL)->setUnique();
        $columns->title = Column::create(Column::STRING + Column::NOT_NULL)->setDefault('');
        $columns->membersCount = Column::INTEGER + Column::NOT_NULL;
        $columns->emailPostfix = Column::STRING;
    }

    /**
     * @param \Yaoi\Database\Definition\Table $table
     * @param \stdClass|static $columns
     */
    static function setUpTable(\Yaoi\Database\Definition\Table $table, $columns)
    {
        $table->setSchemaName('grp');
        Column::cast($columns->ownerId)->setFlag(Column::NOT_NULL, false)->setDefault(null);
    }

    public static function migration()
    {
        $migration = parent::migration();
        return new ClosureMigration(
            'noid',
            function () use ($migration) {
                $migration->apply();
                $globalGroup = new Group();
                $globalGroup->name = 'global';
                $globalGroup->title = 'Global';
                $globalGroup->findOrSave();
                if (0 !== $globalGroup->id) {
                    $globalGroup->id = 0;
                    $globalGroup->save();
                }
            },
            function () use ($migration) {
                $migration->rollback();
            },
            true
        );
    }
}