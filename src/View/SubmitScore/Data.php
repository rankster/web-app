<?php

namespace Rankster\View\SubmitScore;


use Rankster\Entity\Game;
use Rankster\Entity\User;

class Data
{
    private $users = array();
    private $games = array();

    public function addUserInfo(User $user)
    {
        if (!isset($this->users[$user->id])) {
            $this->users[$user->id] = array(
                'name' => $user->name,
                'image' => $user->getFullUrl(),
            );
        }
        return $this;
    }

    public function addGameInfo(Game $game)
    {
        if (!isset($this->games[$game->id])) {
            $this->games[$game->id] = array(
                'name' => $game->name,
                'image' => $game->getFullUrl(),
            );
        }
        return $this;
    }

    public static function getInstance()
    {
        static $instance;
        if (null === $instance) {
            $instance = new self;
        }
        return $instance;
    }

    public function getUsers()
    {
        return $this->users;
    }

    public function getGames()
    {
        return $this->games;
    }

}