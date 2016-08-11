<?php
namespace Rankster;

use Rankster\Api\V1\RecalculateRank;
use Rankster\Api\V1\ReloadFbAvatars;
use Rankster\Command\Migrate;
use Yaoi\Command\Application;
use Yaoi\Command\Definition;

class Tool extends Application
{
    public $migrate;
    public $reloadFbAvatars;
    public $recalc;

    /**
     * @param Definition $definition
     * @param \stdClass|static $commandDefinitions
     */
    static function setUpCommands(Definition $definition, $commandDefinitions)
    {
        $commandDefinitions->migrate = Migrate::definition();
        $commandDefinitions->reloadFbAvatars = ReloadFbAvatars::definition();
        $commandDefinitions->recalc = RecalculateRank::definition();
        $definition->name = 'tool';
    }


}