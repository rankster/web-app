<?php

namespace Rankster\Api\V1\Oauth;

use Yaoi\Command;
use Yaoi\Command\Definition;

class Google extends Command
{
    public $state;
    public $code;

    /**
     * @param Definition $definition
     * @param \stdClass|static $options
     */
    static function setUpDefinition(Definition $definition, $options)
    {
        $options->state = Command\Option::create()->setType()
            ->setDescription('State information (contains initial url)');
        $options->code = Command\Option::create()->setType()->setIsRequired()
            ->setDescription('Auth security code');
    }

    public function performAction()
    {
        $google = \Rankster\Service\Google::getInstance();
        $token = $google->getToken($this->code);
        var_dump($token);
    }


}