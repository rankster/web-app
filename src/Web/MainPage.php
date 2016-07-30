<?php

namespace Rankster\Web;


use Yaoi\Command;
use Yaoi\Command\Definition;

class MainPage extends Command
{
    static function setUpDefinition(Definition $definition, $options)
    {
    }

    public function performAction()
    {
        $this->response->addContent("Hello!");
    }


}