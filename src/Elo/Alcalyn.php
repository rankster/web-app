<?php

namespace Rankster\Elo;

use Alcalyn\Elo\EloSystem;
use Rankster\Entity\Rank;

require_once __DIR__ . '/../../vendor/alcalyn/elo/src/Exception/EloCoefficientException.php';
require_once __DIR__ . '/../../vendor/alcalyn/elo/src/EloSystem.php';

class Alcalyn implements Ranker
{
    public function update(Rank $winner, Rank $loser, $isDraw = false)
    {
        $eloSystem = new EloSystem();

        if ($isDraw) {
            $updatedElos = $eloSystem->draw($winner->rank, $loser->rank);
        } else {
            $updatedElos = $eloSystem->calculate($winner->rank, $loser->rank, 1);
        }

        $winner->rank = $updatedElos[0];
        $loser->rank = $updatedElos[1];
    }


}