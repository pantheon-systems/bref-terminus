<?php declare(strict_types=1);

// Load our dependencies
require dirname(__DIR__).'/vendor/autoload.php';

use Pantheon\Autopilot\Terminus\API;
use Pantheon\Autopilot\Terminus\Env;

/**
 * Creates a lambda function and provides it with an Autopilot Terminus API object.
 *
 * @param string $class_name
 */
function createLambda($class_name): void
{
    $env = Env::createAndFetchSecrets();

    lambda(function ($event) use ($class_name, $env) {
        $api = new Api($env);
        $reflection = new ReflectionClass($class_name);
        $handler = $reflection->newInstanceArgs([$api, ]);

        // 'serverless invoke local' bug: if no parameters are provided,
        // then 'event' is an empty string instead of an empty array.
        if (is_string($event)) {
          $event = [];
        }

        return $handler->handle($event);
    });
}
