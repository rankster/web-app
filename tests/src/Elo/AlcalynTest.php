<?php

namespace Rankster\Tests\Elo;

use Rankster\Elo\Alcalyn;
use Rankster\Elo\Ranker;
use Rankster\Entity\Rank;

require_once __DIR__ . '/../../../env/conf.php';


class AlcalynTest extends \PHPUnit_Framework_TestCase
{

    protected $prevRanker;
    public function setUp()
    {
        $this->prevRanker = Rank::getRanker();
        Rank::setRanker(new Alcalyn());
    }

    public function tearDown()
    {
        Rank::setRanker($this->prevRanker);
    }

    public function testRank()
    {
        $r1 = new Rank();
        $r1->rank = Rank::DEFAULT_RANK;
        $r2 = new Rank();
        $r2->rank = Rank::DEFAULT_RANK;

        $r1->win($r2);
        $this->assertSame(1208.0, round($r1->rank));
        $this->assertSame(1192.0, round($r2->rank));

        $r1->win($r2);
        $this->assertSame(1216.0, round($r1->rank));
        $this->assertSame(1184.0, round($r2->rank));

        $r2->win($r1);
        $this->assertSame(1207.0, round($r1->rank));
        $this->assertSame(1193.0, round($r2->rank));

        $r2->win($r1);
        $this->assertSame(1199.0, round($r1->rank));
        $this->assertSame(1201.0, round($r2->rank));
    }

}