<?php

namespace Rankster\Entity;

use Yaoi\Database\Definition\Column;
use Yaoi\Database\Definition\Table;
use Yaoi\Database\Entity;

class User extends Entity
{
    public $id;
    public $facebookId;
    public $name;
    public $login;
    public $email;
    public $picturePath;
    /**
     * @param \stdClass|static $columns
     */
    static function setUpColumns($columns)
    {
        $columns->id = Column::AUTO_ID;
        $columns->facebookId = Column::create(Column::STRING + Column::NOT_NULL)
            ->setStringLength(50)
            ->setUnique();
        $columns->login = Column::create(Column::STRING)->setUnique();
        $columns->name = Column::STRING + Column::NOT_NULL;
        $columns->email = Column::STRING + Column::NOT_NULL;
        $columns->picturePath = Column::STRING;
    }

    static function setUpTable(\Yaoi\Database\Definition\Table $table, $columns)
    {
        $table->setSchemaName('user');
    }
}
