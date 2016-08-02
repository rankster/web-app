<?php

namespace Rankster\Tests\Rank;


use Rankster\Entity\Game;
use Rankster\Entity\Rank;
use Rankster\Entity\User;

require_once __DIR__ . '/../../../env/conf.php';

class DbTest extends \PHPUnit_Framework_TestCase
{

    public function testSave()
    {
        return;
        $game = new Game();
        $game->name = 'Test';
        $game->findOrSave();

        $user = new User();
        $user->name = 'Dick Cocker';
        $user->findOrSave();

        $rank = new Rank();
        $rank->gameId = $game->id;
        $rank->userId = $user->id;
        $rank->findOrSave();
        $rank->lastUpdateTime = new \DateTime();
        $rank->save();

        $r2 = Rank::findByPrimaryKey($rank->id);
        var_dump($r2->lastUpdateTime);

    }

}