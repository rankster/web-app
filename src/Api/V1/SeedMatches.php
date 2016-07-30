<?php

namespace Rankster\Api\V1;


use Rankster\Entity\Game;
use Rankster\Entity\Match;
use Rankster\Entity\User;
use Rankster\Web\Match\Create;
use Yaoi\Command;
use Yaoi\Command\Definition;

class SeedMatches extends Command
{

    static function setUpDefinition(Definition $definition, $options)
    {
    }

    public function performAction()
    {
        $users = User::statement()->query()->fetchAll();
        $games = Game::statement()->query()->fetchAll();
        $results = array(Match::RESULT_DRAW, Match::RESULT_LOSE, Match::RESULT_WIN);


        for ($i = 0; $i < 10; ++$i) {
            /** @var User $user1 */
            $user1 = $users[rand(0, count($users) - 1)];
            /** @var User $user2 */
            $user2 = $users[rand(0, count($users) - 1)];
            if ($user1->id === $user2->id) {
                continue;
            }
            /** @var Game $game */
            $game = $games[rand(0, count($games) - 1)];

            $result = $results[rand(0, count($results) - 1)];

            Match::make($user1->id, $user2->id, $game->id, $result);
        }
    }


}