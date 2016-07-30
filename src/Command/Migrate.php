<?php

namespace Rankster\Command;

use Rankster\Entity\RankHistory;
use Rankster\Entity\Session;
use Yaoi\Command;
use Yaoi\Command\Definition;
use Yaoi\Database\Definition\Table;
use Yaoi\Log;
use Rankster\Entity\Game;
use Rankster\Entity\User;
use Rankster\Entity\Match;
use Rankster\Entity\Rank;

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
            Session::table(),
            User::table(),
            Game::table(),
            Match::table(),
            Rank::table(),
            RankHistory::table(),
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

        // Fixtures Generator
        $game = new Game();
        $game->name = 'Ping Pong';
        $game->picturePath = '/../images/pingpong.png';
        $game->findOrSave();

        $game = new Game();
        $game->name = 'Battroborg Thumb War';
        $game->picturePath = '/../images/robots256x256.jpg';
        $game->findOrSave();

        $game = new Game();
        $game->name = 'Soccer Table';
        $game->picturePath = '/../images/soccertable256x256';
        $game->findOrSave();

        $game = new Game();
        $game->name = 'Co Tuong';
        $game->picturePath = '/../images/vietchess256x256';
        $game->findOrSave();

        $game = new Game();
        $game->name = 'Paper Rock Cissor';
        $game->picturePath = '/../images/ShiFuMi256x256.png';
        $game->findOrSave();


//       add users
        $user = new User();
        $user->facebookId = '10153792250453603';
        $user->name = 'Alexis Grohar';
        $user->picturePath = '/fb5/c5c9e9261d500eb3ffd1b3a58e811.jpg';
        $user->findOrSave();

//       $user->login = '';
//       $user->email = '';
//       $user->downloadImage('');
//       $user->findOrSave();


    }

}