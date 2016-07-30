<?php

namespace Rankster\Api;


use Rankster\Api\V1\Update;
use Yaoi\Command;
use Yaoi\Command\Definition;

class V1 extends Command
{
    /**
     * @var Command
     */
    public $action;

    /**
     * @param Definition $definition
     * @param \stdClass|static $options
     */
    static function setUpDefinition(Definition $definition, $options)
    {
        $options->action = Command\Option::create()
            ->addToEnum(Update::definition())
            ->setIsUnnamed();

    }

    public function performAction()
    {
        header("Content-Type: text/javascript");
        echo json_encode(array('iam' => 'api'));
        exit();
    }


}