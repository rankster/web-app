<?php

namespace Rankster\Api\V1;


use Yaoi\Command;
use Yaoi\Command\Definition;

class Update extends Command
{

    /**
     * @param Definition $definition
     * @param \stdClass|static $options
     */
    static function setUpDefinition(Definition $definition, $options)
    {
    }

    public function performAction()
    {
        $path = __DIR__ . '/../../..';
        $path = realpath($path);
        header("Content-Type: text/plain");
        system("cd $path; git pull; composer install; bin/tool migrate;");
        exit();
    }


}