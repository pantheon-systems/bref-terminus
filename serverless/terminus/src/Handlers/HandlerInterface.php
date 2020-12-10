<?php declare(strict_types=1);

namespace Pantheon\Autopilot\Terminus\Handlers;

/**
 * Interface HandlerInterface
 * @package Pantheon\Autopilot\Terminus\Handlers
 */
interface HandlerInterface
{
    /**
     * Handle the lambda event. Main entrypoint for the lambda function.
     *
     * We will automatically create a login session before calling the lambda
     * action method.
     *
     * @param array $event
     *   Event parameters passed in from `serverless`
     *
     * @return array
     *   Result of the lambda function.
     */
    public function handle(array $event);
}
