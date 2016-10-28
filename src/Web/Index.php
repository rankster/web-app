<?php

namespace Rankster\Web;

use Rankster\Api\Admin;
use Rankster\Api\V1;
use Rankster\Command\AuthRequired;
use Rankster\Web\Forms\SubmitScore;
use Rankster\Web\Game\Game;
use Rankster\Web\Match\Match;
use Rankster\Web\MatchRequest\MatchRequest;
use Rankster\Web\User\User;
use Yaoi\Command;
use Yaoi\Command\Definition;
use Yaoi\Database\Definition\Exception;

class Index extends Command
{
    /**
     * @var Command
     */
    public $action;

    /**
     * Required setup option types in provided options object
     * @param $definition Definition
     * @param $options static|\stdClass
     */
    static function setUpDefinition(Definition $definition, $options)
    {
        $options->action = Command\Option::create()
            ->setDescription('Root action')
            ->setIsUnnamed()
            ->addToEnum(Login::definition())
            ->addToEnum(Logout::definition())
            ->addToEnum(MainPage::definition(), '')
            ->addToEnum(SubmitScore::definition(), 'submit-score')
            ->addToEnum(Game::definition())
            ->addToEnum(Match::definition())
            ->addToEnum(User::definition())
            ->addToEnum(MatchRequest::definition(), 'match-request')
            ->addToEnum(V1::definition(), 'v1')
            ->addToEnum(Admin::definition(), 'admin')
            ->addToEnum(V1\Update::definition(), 'git-update');

        $definition->description = 'Ranking service';
    }

    public function performAction()
    {
        $this->action->performAction();
    }

}