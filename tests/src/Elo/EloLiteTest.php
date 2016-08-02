<?php

namespace Rankster\Tests\Elo;


use Rankster\Elo\EloLite;
use Rankster\Entity\Rank;

class EloLiteTest extends AlcalynTest
{
    public function setUp()
    {
        $this->prevRanker = Rank::getRanker();
        Rank::setRanker(new EloLite());
    }

}