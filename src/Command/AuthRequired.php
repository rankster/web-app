<?php

namespace Rankster\Command;

use Rankster\Api\ClientException;
use Rankster\Entity\Session;
use Yaoi\Command;

abstract class AuthRequired extends Command
{
    const TOKEN = 'token';
    public $userId;

    public function initSession() {
        if (!isset($_COOKIE[AuthRequired::TOKEN])) {
            throw new ClientException("Session token required");
        }
        $session = Session::findByPrimaryKey($_COOKIE[AuthRequired::TOKEN]);
        if (!$session) {
            throw new ClientException("Bad session token");
        }
        $this->userId = $session->userId;
    }
}