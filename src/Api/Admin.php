<?php

namespace Rankster\Api;

use Rankster\Api\Admin\AddUser;
use Rankster\Api\Admin\DeleteUser;
use Rankster\Api\Admin\RecalculateRank;
use Rankster\Api\Admin\ReloadFbAvatars;
use Rankster\Api\Admin\SeedMatches;
use Rankster\Api\Admin\WipeRank;
use Yaoi\Command;
use Yaoi\Http\Auth;

class Admin extends Api
{
    /**
     * @param Command\Definition $definition
     * @param \stdClass|static $options
     */
    static function setUpDefinition(Command\Definition $definition, $options)
    {
        $options->action = Command\Option::create()
            ->addToEnum(WipeRank::definition(), 'wipe-rank')
            ->addToEnum(RecalculateRank::definition(), 'recalculate-rank')
            ->addToEnum(AddUser::definition(), 'add-user')
            ->addToEnum(SeedMatches::definition(), 'seed-matches')
            ->addToEnum(DeleteUser::definition(), 'delete-user')
            ->addToEnum(ReloadFbAvatars::definition())
            ->setIsRequired()
            ->setIsUnnamed();
    }

    public function performAction()
    {
        Auth::getInstance('admin')->demand();
        parent::performAction();
    }
}