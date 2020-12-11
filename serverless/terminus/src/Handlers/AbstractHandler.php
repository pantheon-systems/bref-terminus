<?php declare(strict_types=1);

namespace Pantheon\Autopilot\Terminus\Handlers;

use Pantheon\Autopilot\Terminus\API;

/**
 * Class AbstractHandler
 * @package Pantheon\Autopilot\Terminus\Handlers
 */
abstract class AbstractHandler implements HandlerInterface
{
    /**
     * @var API
     */
    protected $api;

    /**
     * Handler constructor
     *
     * @param API $api
     *   Terminus API object
     * @param array $env
     *   Environment variables relevant to the handler
     */
    public function __construct(API $api)
    {
        $this->api = $api;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(array $event)
    {
        $this->validate($event);
        return $this->action($event);
    }

    /**
     * Optionally validate the contents of the event parameters.
     *
     * Throw an exception if any required information is missing. Happens
     * prior to login (fail-fast).
     *
     * @param array $event
     *   Event parameters passed in from `serverless`
     */
    public function validate(array $event): void
    {
    }

    /**
     * Implementation for the lambda function.
     *
     * @param array $event
     *   Event parameters passed in from `serverless`
     *
     * @return array
     *   Result of the lambda function.
     */
    abstract public function action(array $event);

    /**
     * Validate that the provided event contains all required parameters.
     *
     * @param array $event
     *   Serverless event array.
     * @param array $required
     *   List of keys that must exist in the event array.
     */
    protected function checkRequiredEventParameters(array $event, array $required): void
    {
        $error_map = [
            'env' => 'No environment specified.',
            'mode' => 'No mode specified.',
            'password' => 'No password specified',
            'site' => 'No site specified.',
            'source' => 'No source environment specified.',
            'message' => 'No commit message specified.',
            'username' => 'No username specified.',
            'target' => 'No target environment specified.',
            'workflow' => 'No workflow UUID specified.',
            'annotation' => 'No deployment annotation provided.'
        ];

        foreach ($required as $key) {
            if (!isset($error_map[$key])) {
                throw new \Error("Error: handler attempted to validate an unknown event parameter '$key'");
            }
            if (!isset($event[$key])) {
                throw new \RuntimeException($error_map[$key]);
            }
        }
    }
}
