<?php

namespace Rankster\Web;

use Rankster\Entity\Game;
use Rankster\Service\AuthSession;
use Rankster\Service\Session;
use Rankster\View\SubmitScore\Data;
use Rankster\View\UserRankTable;
use Yaoi\Command;
use Yaoi\Command\Definition;

class MainPage extends Command
{
    static function setUpDefinition(Definition $definition, $options)
    {
    }

    public function performAction()
    {
        if (AuthSession::justLoggedIn()) {
            $this->response->success("Successfully logged in!");
            $this->response->addContent('<script>setTimeout(function(){$(".alert-success").hide(500);}, 3000)</script>');
        }

        $messages = Session::getSuccessMessages();
        if (!empty($messages)) {
            foreach ($messages as $message) {
                $this->response->success($message);
            }
        }

        $limit = 2;
        if ((!$user = AuthSession::getUser()) || !$games = $user->getMostPlayedGames($limit)) {
            $games = Game::statement()
                ->order('? DESC', Game::columns()->playersCount)
                ->limit($limit)->query()->fetchAll();
        }

        $this->response->addContent('<div class="row">');
        /** @var Game $game */
        foreach ($games as $game) {

            $ranks = \Rankster\Entity\Rank::getRanks($game->id, 10);
            $table = UserRankTable::create();
            $table->game = $game;
            $table->userRanks = $ranks;

            $this->response->addContent((string)$table);

        }
        $this->response->addContent('</div>');
    }

}
