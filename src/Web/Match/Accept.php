<?php

namespace Rankster\Web\Match;


use Yaoi\Command;
use Yaoi\Command\Definition;

class Accept extends Command
{
    public $matchId;
    public $alwaysAccept;

    /**
     * @param Definition $definition
     * @param \stdClass|static $options
     */
    static function setUpDefinition(Definition $definition, $options)
    {
    }

    /**
     *
     */
    public function performAction()
    {
        // TODO: Implement performAction() method.
    }


}