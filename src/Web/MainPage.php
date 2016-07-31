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
        if (isset($_SESSION['logged_in_redirect'])) {
            unset($_SESSION['logged_in_redirect']);
            $this->response->success("Successfully logged in!");
            $this->response->addContent('<script>setTimeout(function(){$(".alert-success").hide(500);}, 3000)</script>');
        }
        $this->response->addContent(Output::process('Header'));
        $this->response->addContent(Output::process('MainPage_tables'));
    }

}
