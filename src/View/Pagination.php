<?php
namespace Rankster\View;


use Yaoi\Command\Io;
use Yaoi\Command\Option;
use Yaoi\Io\Content\Anchor;
use Yaoi\View\Hardcoded;

class Pagination extends Hardcoded
{
    private $result;

    public function __construct($state, Io $io, Option $option, $pages)
    {
        $optionName = $option->name;

        $page = $state->$optionName;
        $this->result = <<<HTML
<nav>
  <ul class="pager">

HTML;

        if ($page > 1) {
            $state->$optionName = $page - 1;
            $url = $io->makeAnchor($state);
            $this->result .= <<<HTML
    <li class="previous"><a href="{$url}"><span>&larr;</span></a></li>

HTML;
        }

        if ($page < $pages) {
            $state->$optionName = $page + 1;
            $url = $io->makeAnchor($state);
            $this->result .= <<<HTML
    <li class="next"><a href="{$url}"><span>&rarr;</span></a></li>

HTML;
        }

        $this->result .= <<<HTML
  </ul>
</nav>

HTML;

    }

    public function render()
    {
        echo $this->result;
    }


}