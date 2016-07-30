<?php

namespace Rankster\Api\V1;

use Yaoi\Command;
use Yaoi\Command\Definition;
use YaoiTests\Helper\Entity\User;

class Users extends Command
{
    public $q;
    public $p;
    /**
     * Required setup option types in provided options object
     * @param $definition Definition
     * @param $options static|\stdClass
     */
    static function setUpDefinition(Definition $definition, $options)
    {
        $options->p = Command\Option::create()->setType();
        $options->q = Command\Option::create()->setType();

    }

    public function performAction()
    {
        $perPage = 20;
        $page = $this->p;

        $st = User::statement()
            ->order('? ASC', User::columns()->name);
        if ($this->q) {
           $st->where('? LIKE ?', User::columns()->name, $this->q . '%');
        }

        $stCnt = clone($st);
        $stCnt->select('COUNT(*) as cnt');

        $queryCnt = $stCnt->query();
        $queryCnt->bindResultClass();
        $cnt = $queryCnt->fetchRow();

        $st->limit($perPage, max(0, $perPage * ($page - 1)));
        $query = $st->query();
        $data = $query->fetchAll();
        $response = [
            'total_count'   => $cnt['cnt'],
            'items'         => [],
        ];
        /** @var Game $game */
        foreach ($data as $game) {
            $response['items'][] = [
                'id'        => $game->id,
                'name'      => $game->name,
                'picture'   => $game->getFullUrl(),
            ];
        }

        return $response;
    }
}
