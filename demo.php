<?php

declare(strict_types=1);

use NunoMaduro\Collision\Provider;
use Oct8pus\Stocks\Cli\Commands;

require_once __DIR__ . '/vendor/autoload.php';

(new Provider())
    ->register();

(new Commands($argv))
    ->run();
