<?php

namespace Rankster\Web\Forms;


use Rankster\Twbs\Io\Content\Form;
use Yaoi\Command;
use Yaoi\Command\Definition;

class SubmitScore extends Command
{
    static function setUpDefinition(Definition $definition, $options)
    {
    }

    public function performAction()
    {
        $form = new Form(\Rankster\Api\V1\SubmitScore::createState(), $this->io);
        $this->response->addContent($form);
    }


}