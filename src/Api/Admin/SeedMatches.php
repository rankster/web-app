<?php

namespace Rankster\Api\Admin;


use Rankster\Entity\Game;
use Rankster\Entity\Match;
use Rankster\Entity\User;
use Rankster\Web\Match\Create;
use Yaoi\Command;
use Yaoi\Command\Definition;

class SeedMatches extends Command
{

    public $count = 10;
    public $gameId = 0;

    /**
     * @param Definition $definition
     * @param \stdClass|static $options
     */
    static function setUpDefinition(Definition $definition, $options)
    {
        $options->count = Command\Option::create()->setType();
        $options->gameId = Command\Option::create()->setType();
    }

    public function performAction()
    {
        $users = User::statement()->query()->fetchAll();
        $games = Game::statement()->query()->fetchAll();
        $results = array(Match::RESULT_DRAW, Match::RESULT_LOSE, Match::RESULT_WIN);


        for ($i = 0; $i < $this->count; ++$i) {
            /** @var User $user1 */
            $user1 = $users[rand(0, count($users) - 1)];
            /** @var User $user2 */
            $user2 = $users[rand(0, count($users) - 1)];
            if ($user1->id === $user2->id) {
                continue;
            }
            /** @var Game $game */
            $game = $games[rand(0, count($games) - 1)];
            $gameId = $game->id;
            if ($this->gameId) {
                $gameId = $this->gameId;
            }

            $result = $results[rand(0, count($results) - 1)];

            Match::make($user1->id, $user2->id, $gameId, $result)->applyRanks();
        }
    }


}