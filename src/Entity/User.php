<?php

namespace Rankster\Entity;

use Yaoi\Database\Definition\Column;
use Yaoi\Database\Definition\Table;
use Yaoi\Database\Entity;
use Yaoi\Undefined;

class User extends Entity
{
    const IMAGE_FOLDER = 'user-images';

    public $id;
    public $facebookId;
    public $googleId;
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
        $columns->facebookId = Column::create(Column::STRING)
            ->setStringLength(50)
            ->setUnique();
        $columns->googleId = Column::create(Column::STRING)->setUnique();
        $columns->login = Column::create(Column::STRING)->setUnique();
        $columns->name = Column::create(Column::STRING + Column::NOT_NULL)->setDefault('');
        $columns->email = Column::create(Column::STRING + Column::NOT_NULL)->setDefault('')->setUnique();
        $columns->picturePath = Column::STRING;
    }

    static function setUpTable(\Yaoi\Database\Definition\Table $table, $columns)
    {
        $table->setSchemaName('user');
    }
    
    public function downloadImage($url, $id = null)
    {
        $response = file_get_contents($url);
        if (!$response) {
            return;
        }

        if (null === $id) {
            $id = $this->facebookId;
        }
        $md5 = md5($id . 'FacebookHackathon2016');
        $dir = substr($md5, 0, 3);

        $imagesDirectory = DOC_ROOT . '/' . self::IMAGE_FOLDER . '/';

        if (!file_exists($imagesDirectory . $dir)) {
            mkdir($imagesDirectory . $dir, 0777, true);
        }

        $this->picturePath = '/' . $dir . '/' . substr($md5, 3) . '.jpg';

        umask(0);
        $f = fopen($imagesDirectory . $this->picturePath, 'w');
        fwrite($f, $response);
        fclose($f);

        return true;
    }

    public function getFullUrl()
    {
        if (!$this->picturePath || $this->picturePath instanceof Undefined) {
            return null;
        }

        return self::pathToUrl($this->picturePath);
    }

    public static function pathToUrl($path)
    {
        return '//' . $_SERVER['HTTP_HOST'] . '/' . self::IMAGE_FOLDER . $path;
    }

    public function findLastMatch()
    {
        $cols = Match::columns();
        /** @var Match $last */
        $last = Match::statement()->where("? = ? OR ? = ?", $cols->user1Id, $this->id, $cols->user2Id, $this->id)
            ->order("? DESC", $cols->id)
            ->limit(1)->query()->fetchRow();

        if (!$last) {
            return null;
        }

        $opponentId = $last->user1Id === $this->id ? $last->user2Id : $last->user1Id;
        return array('user' => User::findByPrimaryKey($opponentId), 'game' => Game::findByPrimaryKey($last->gameId));
    }

    private $gameIds;
    public function getGameIds()
    {
        if (null === $this->gameIds) {
            $this->gameIds = Rank::statement()
                ->where('? = ?', Rank::columns()->userId, $this->id)
                ->query()->fetchAll(Rank::columns()->gameId, Rank::columns()->gameId);
        }
        return $this->gameIds;
    }

    public function getLevelCaption()
    {
        return 'Rookie';
    }

    /**
     * @param int $limit
     * @return Game[]
     */
    public function getLastPlayedGames($limit = 2)
    {
        $games = Game::statement()->select('?.*', Game::table())
            ->innerJoin('? ON ? = ?', Rank::table(), Rank::columns()->gameId, Game::columns()->id)
            ->where('? = ?', Rank::columns()->userId, $this->id)
            ->order('? DESC', Rank::columns()->lastUpdateTime)
            ->limit($limit)->bindResultClass(Game::className())->query()->fetchAll();

        return $games;
    }

    /**
     * @param int $limit
     * @return Game[]
     */
    public function getMostPlayedGames($limit = 2)
    {
        $games = Game::statement()->select('?.*', Game::table())
            ->innerJoin('? ON ? = ?', Rank::table(), Rank::columns()->gameId, Game::columns()->id)
            ->where('? = ?', Rank::columns()->userId, $this->id)
            ->order('? DESC', Rank::columns()->matches)
            ->limit($limit)->bindResultClass(Game::className())->query()->fetchAll();

        return $games;
    }

    public function getMatchRequestNewCount()
    {
        return MatchRequest::getCountNew($this->id);
    }

    public static function findByPrimaryKey($id)
    {
        static $cache = array();
        if (isset($cache[$id])) {
            return $cache[$id];
        }

        $entity = parent::findByPrimaryKey($id);
        $cache[$id] = $entity;
        return $entity;
    }
}
