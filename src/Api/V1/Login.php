<?php

namespace Rankster\Api\V1;

use Rankster\Service\Facebook;
use Yaoi\Command;
use Yaoi\Command\Definition;

class Login extends Command
{
    /**
     * Required setup option types in provided options object
     * @param $definition Definition
     * @param $options static|\stdClass
     */
    static function setUpDefinition(Definition $definition, $options)
    {
        
    }

    public function performAction()
    {
        var_dump(Facebook\User::getInstance()->getData());

        return ['ok' => true];
    }
}
