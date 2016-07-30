<?php

namespace Rankster;

use Rankster\Web\Index;
use Rankster\Twbs\Runner;

error_reporting(E_ALL);
ini_set("display_errors", 1);

define('DOC_ROOT', __DIR__);

session_start();
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../env/conf.php';

Runner::create()->run(Index::definition());