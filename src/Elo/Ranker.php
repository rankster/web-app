<?php

namespace Rankster\Elo;

use Rankster\Entity\Rank;

interface Ranker
{
    public function update(Rank $winner, Rank $loser, $isDraw = false);
}