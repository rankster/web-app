<?php

namespace Rankster\Web;


use Rankster\Service\Output;
use Yaoi\Command;
use Yaoi\Command\Definition;

class MainPage extends Command
{
    static function setUpDefinition(Definition $definition, $options)
    {
    }

    public function performAction()
    {
      $this->response->addContent(Output::process('Header'));
      if (isset($_SESSION['logged_in_redirect'])) {
        unset($_SESSION['logged_in_redirect']);
        $this->response->success("Successfuly logged in !");
      }
        $this->response->addContent(Output::process('MainPage_tables'));
    }

}
