<?php

namespace Rankster;

use Rankster\Web\Index;
use Yaoi\Twbs\Runner;

trigger_error($_SERVER['REQUEST_URI']);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../env/conf.php';

Runner::create()->run(Index::definition());