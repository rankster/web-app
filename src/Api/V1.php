<?php

namespace Rankster\Api;


use Rankster\Api\V1\Games;
use Rankster\Api\V1\Login;
use Rankster\Api\V1\Oauth\Oauth;
use Rankster\Api\V1\Users;
use Rankster\Api\V1\SubmitScore;
use Rankster\Api\V1\Update;
use Yaoi\Command;
use Yaoi\Command\Definition;

class V1 extends Api
{
    /**
     * @param Definition $definition
     * @param \stdClass|static $options
     */
    static function setUpDefinition(Definition $definition, $options)
    {
        $options->action = Command\Option::create()
            ->addToEnum(Update::definition())
            ->addToEnum(Login::definition(), 'login')
            ->addToEnum(Games::definition(), 'games')
            ->addToEnum(Users::definition(), 'users')
            ->addToEnum(SubmitScore::definition(), 'submit-score')
            ->addToEnum(Oauth::definition())
            ->setIsUnnamed();
    }
}