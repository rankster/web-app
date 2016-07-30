<?php
namespace Rankster;

use Rankster\Command\Migrate;
use Yaoi\Command\Application;
use Yaoi\Command\Definition;

class Tool extends Application
{
    public $migrate;

    /**
     * @param Definition $definition
     * @param \stdClass|static $commandDefinitions
     */
    static function setUpCommands(Definition $definition, $commandDefinitions)
    {
        $commandDefinitions->migrate = Migrate::definition();
        $definition->name = 'tool';
    }


}