<?php

namespace Rankster\Web;


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
      $this->response->addContent(file_get_contents("./Static/MainPage_tables.html"));

        $ss = <<<HTML
<p>Yeah, {$this->bitch}</p>
HTML;
        $this->response->addContent($ss);

    }


}
