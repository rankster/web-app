<?php

namespace Rankster\Api\V1;

use Rankster\Entity\Game;
use Yaoi\Command;
use Yaoi\Command\Definition;

class Games extends Command
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

        $st = Game::statement()
            ->order('? DESC', Game::columns()->name);
        if ($this->q) {
           $st->where('? LIKE ?', Game::columns()->name, $this->q . '%');
        }

        $stCnt = clone($st);
        $stCnt->select('COUNT(*) as cnt');

        $queryCnt = $stCnt->query();
        $queryCnt->bindResultClass();
        $cnt = $queryCnt->fetchRow();

        $st->limit($perPage);
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
