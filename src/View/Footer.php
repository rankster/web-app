<?php

namespace Rankster\View;


use Rankster\View\SubmitScore\Data;
use Yaoi\View\Hardcoded;

class Footer extends Hardcoded
{
    public function render()
    {
        $games = Data::getInstance()->getGames();
        $users = Data::getInstance()->getUsers();
        if ($games || $users) {
            $games = json_encode($games);
            $users = json_encode($users);
            echo <<<HTML
<script>
Rankster.setUserGameInfo({$users},{$games});
</script>
HTML;
        }
    }
}