<?php

namespace Rankster\Web;


use Rankster\Service\AuthSession;
use Yaoi\Command;
use Yaoi\Command\Definition;

class Logout extends Command
{
    static function setUpDefinition(Definition $definition, $options)
    {
        // TODO: Implement setUpDefinition() method.
    }

    public function performAction()
    {
        AuthSession::clear();
        header("Location: /");
    }


}