<?php
namespace Rankster\View;


use Yaoi\Command\Io;
use Yaoi\Command\Option;
use Yaoi\Command\State;
use Yaoi\Sql\Statement;
use Yaoi\View\Hardcoded;

class Pagination extends Hardcoded
{
    private $result;

    public static function createFromSqlStatement(Statement $statement, State $state, Option $pageNumber, $perPage = 10)
    {
        $count = $statement->copy()->select('COUNT(1) as c')->query()->fetchRow('c');
        $pages = ceil($count / $perPage);
        return new Pagination($state, $pageNumber, $pages);
    }

    public function __construct(State $state, Option $pageNumber, $pages)
    {
        $optionName = $pageNumber->name;
        $page = (int)$state->$optionName;

        if (empty($page)) {
            $page = 1;
        }

        $this->result = <<<HTML
<nav>
  <ul class="pager">

HTML;

        if ($page > 1) {
            $state->$optionName = $page - 1;
            $url = $state->makeAnchor();
            $this->result .= <<<HTML
    <li class="previous"><a href="{$url}"><span>&larr;</span></a></li>

HTML;
        }

        if ($page <= $pages) {
            $state->$optionName = $page + 1;
            $url = $state->makeAnchor();
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