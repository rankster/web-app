<?php

namespace Rankster;

use Rankster\Service\FacebookLogin;
use Yaoi\Database;

Database::register('mysqli://root:password@localhost/rankster');

FacebookLogin::register('', 'app_id');
FacebookLogin::register('', 'app_secret');
FacebookLogin::register('2.2', 'default_graph_version');
FacebookLogin::register('http://rankster.penix.tk/v1/login', 'callback_url');
