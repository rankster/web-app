<?php

namespace Rankster\Entity;

use Yaoi\Database\Definition\Column;
use Yaoi\Database\Definition\Table;
use Yaoi\Database\Entity;

class Game extends Entity
{
    public $id;
    public $name;
    public $picturePath;
    /**
     * @param \stdClass|static $columns
     */
    static function setUpColumns($columns)
    {
        $columns->id = Column::AUTO_ID;
        $columns->name = Column::create(Column::STRING + Column::NOT_NULL)->setUnique();
        $columns->picturePath = Column::STRING;
    }

    static function setUpTable(\Yaoi\Database\Definition\Table $table, $columns)
    {
        $table->setSchemaName('game');
    }
}
