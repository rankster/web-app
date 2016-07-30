<?php

namespace Rankster\Entity;

use Yaoi\Database\Definition\Column;
use Yaoi\Database\Definition\Table;
use Yaoi\Database\Entity;
use Yaoi\Undefined;

class Game extends Entity
{
    const IMAGE_FOLDER = 'user-images';

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

    public function getFullUrl()
    {
        if (!$this->picturePath || $this->picturePath instanceof Undefined) {
            return null;
        }

        return self::patchToUrl($this->picturePath);
    }

    public static function patchToUrl($path)
    {
        return '//' . $_SERVER['HTTP_HOST'] . '/' . self::IMAGE_FOLDER . $path;
    }

    public function countPlayers()
    {
        $r = Rank::statement()->select('COUNT(1) AS c')->where("? = ?", Rank::columns()->gameId, $this->id)
            ->bindResultClass()
            ->query()->fetchRow();
        return $r['c'];
    }
}
