<?php declare(strict_types=1);

require dirname(__DIR__).'/entrypoints/entrypoint.php';

use Pantheon\Autopilot\Terminus\Handlers\CloneFilesHandler;

createLambda(CloneFilesHandler::class);
