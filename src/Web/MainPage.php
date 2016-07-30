<?php

namespace Rankster\Web;


use Rankster\Service\Output;
use Yaoi\Command;
use Yaoi\Command\Definition;

class MainPage extends Command
{
    public $bitch = "Hello!";

    static function setUpDefinition(Definition $definition, $options)
    {
    }

    public function performAction()
    {
        $this->response->success("Hello! Test From Alexis");
        $this->response->addContent(Output::process('MainPage_tables'));
    }

}
