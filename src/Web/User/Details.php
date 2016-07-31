<?php

namespace Rankster\Web\User;

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
        $ranks = \Rankster\Entity\Rank::getRanksByUser($this->userId);
        $table = UserRankTable::create();
        $table->
        $this->response->addContent();
    }

}