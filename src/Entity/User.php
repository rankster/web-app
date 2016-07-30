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
    
    public function downloadImage($url)
    {
        $ch = curl_init($url);
        curl_setopt_array(
            $ch,
            [
                CURLOPT_RETURNTRANSFER  => true,
            ]
        );

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if (curl_errno($ch) || $httpCode != 200) {
            return false;
        }

        $md5 = md5($this->facebookId . 'FacebookHackathon2016');
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

        return '//' . $_SERVER['HTTP_HOST'] . '/' . self::IMAGE_FOLDER . $this->picturePath;
    }
}
