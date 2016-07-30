<?php

namespace Rankster\Command;

use Yaoi\Command;
use Yaoi\Command\Definition;
use Yaoi\Database\Definition\Table;
use Yaoi\Log;

class Migrate extends Command
{
    public $wipe;
    public $dryRun;

    static function setUpDefinition(Definition $definition, $options)
    {
        $options->wipe = Command\Option::create()->setDescription('Recreate tables');
        $options->dryRun = Command\Option::create()->setDescription('Read-only mode');
        $definition->name = 'migrate';
        $definition->description = 'Actualize application data schema';
    }

    public function performAction()
    {
        /** @var Table[] $tables */
        $tables = array(
            // put your tables here
            //Entity::table(),
        );

        $log = new Log('colored-stdout');
        if ($this->wipe) {
            foreach ($tables as $table) {
                $table->migration()->setLog($log)->setDryRun($this->dryRun)->rollback();
            }
        }
        foreach ($tables as $table) {
            $table->migration()->setLog($log)->setDryRun($this->dryRun)->apply();
        }
    }

}