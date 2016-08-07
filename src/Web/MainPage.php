<?php

namespace Rankster\Web;


use Rankster\Entity\Game;
use Rankster\Entity\Rank;
use Rankster\Service\AuthSession;
use Rankster\Service\Output;
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
        $this->response->addContent(Output::process('Header'));

        $count = 2;
        if ($userId = AuthSession::getUserId()) {
            $games = Game::statement()->select('?.*', Game::table())
                ->innerJoin('? ON ? = ?', Rank::table(), Rank::columns()->gameId, Game::columns()->id)
                ->where('? = ?', Rank::columns()->userId, $userId)
                ->order('? DESC', Rank::columns()->lastUpdateTime)
                ->limit($count)->bindResultClass(Game::className())->query()->fetchAll(null, Rank::columns()->gameId);
        } else {
            $games = Game::statement()
                ->order('? DESC', Game::columns()->playersCount)
                ->limit($count)->query()->fetchAll();

        }

        $this->response->addContent('<div class="row">');
        /** @var Game $game */
        foreach ($games as $game) {

            $ranks = \Rankster\Entity\Rank::getRanks($game->id, 10);
            $table = UserRankTable::create();
            $table->name = $game->name;
            $table->image = $game->getFullUrl();
            $table->userRanks = $ranks;

            $this->response->addContent((string)$table);

        }
        $this->response->addContent('</div>');
        $this->response->addContent('<script>Rankster.setUserGameInfo('
            . json_encode(Data::getInstance()->getUsers()) . ', '
            . json_encode(Data::getInstance()->getGames()) . ')</script>');


        //$this->response->addContent(Output::process('MainPage_tables'));
    }

}
