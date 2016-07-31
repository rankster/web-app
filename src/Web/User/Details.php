<?php

namespace Rankster\Web\User;

use Rankster\Entity\User;
use Rankster\View\UserRankTable;
use Yaoi\Command;
use Yaoi\Command\Definition;

class Details extends Command
{
    public $userId;

    /**
     * @param Definition $definition
     * @param \stdClass|static $options
     */
    static function setUpDefinition(Definition $definition, $options)
    {
        $options->userId = Command\Option::create()->setType()->setIsRequired();
    }

    public function performAction()
    {
        $user = User::findByPrimaryKey($this->userId);
        $ranks = \Rankster\Entity\Rank::getRanksByUser($this->userId);
        $table = UserRankTable::create();
        $table->name = $user->name;
        $table->image = $user->getFullUrl();
        $table->userRanks = $ranks;
        $this->response->addContent((string)$table);
    }

}