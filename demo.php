<?php

declare(strict_types=1);

use NunoMaduro\Collision\Provider;
use Oct8pus\Stocks\Cli\Commands;
use Swew\Cli\Terminal\Output;

require_once __DIR__ . '/vendor/autoload.php';

(new Provider())
    ->register();

$stdin = fopen('php://stdin', 'r');

if ($stdin === false) {
    throw new Exception('fopen');
}

$input = $argv;

do {
    (new Commands($input, new Output(), false))
        ->run();

    echo "\n> ";
    $input = trim(fgets($stdin));

    if (in_array($input, ['', 'exit', 'quit'])) {
        break;
    }

    $input = explode(' ', "dummy {$input}");
} while (true);

fclose($stdin);
exit(0);
