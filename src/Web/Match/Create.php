<?php

namespace Rankster\Web\Match;

use Rankster\Entity\Match;
use Yaoi\Command;
use Yaoi\Command\Definition;

class Create extends Command
{


    static function setUpDefinition(Definition $definition, $options)
    {


        // TODO: Implement setUpDefinition() method.
    }

    public function performAction()
    {

        $this->response->addContent('<pre>' . print_r($_SESSION, 1) . '</pre>');

        $match = new Match();
        // TODO: Implement performAction() method.
    }


}