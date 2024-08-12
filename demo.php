<?php

declare(strict_types=1);

use Oct8pus\Stocks\Cli\Commands;
use NunoMaduro\Collision\Provider;

require_once __DIR__ . '/vendor/autoload.php';

(new Provider())
    ->register();

(new Commands($argv))
    ->run();
