<?php

namespace Rankster\Web;

use Rankster\Api\V1;
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
            ->addToEnum(MainPage::definition(), '/')
            ->addToEnum(V1::definition(), 'v1')
            ->addToEnum(V1\Update::definition(), 'git-update')
        ;

        $definition->description = 'Ranking service';
    }

    public function performAction()
    {
        try {
            $this->action->performAction();
        } catch (Exception $e) {
            var_dump($e->query);
        }
    }

}