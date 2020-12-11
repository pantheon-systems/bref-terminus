<?php declare(strict_types=1);

namespace Pantheon\Autopilot\Terminus\Handlers;

use Pantheon\Autopilot\Terminus\Traits\WorkflowProcessingTrait;
use Pantheon\Terminus\Exceptions\TerminusException;

/**
 * Class SetConnectionHandler
 * @package Pantheon\Autopilot\Terminus\Handlers
 */
class SetConnectionHandler extends AbstractHandler
{
    use WorkflowProcessingTrait;

    /**
     * {@inheritdoc}
     *
     * The $event array must contain:
     *
     *   - 'env': The name of the target environment.
     *   - 'mode': The type of the connection.
     *   - 'site': The site name or site id of the target site.
     */
    public function validate(array $event): void
    {
        $this->checkRequiredEventParameters($event, ['env', 'mode', 'site']);
        if ($event['mode'] != 'git' && $event['mode'] != 'sftp') {
            throw new \RuntimeException("Mode must be either `git` or `sftp`.");
        }
    }

    /**
     * {@inheritdoc}
     *
     * @see self::validate for expected parameters in $event array.
     *
     * @return array
     * @throws \Pantheon\Terminus\Exceptions\TerminusException
     * @throws \Pantheon\Terminus\Exceptions\TerminusNotFoundException
     */
    public function action(array $event): array
    {
        $sites = $this->api->sites();
        $site = $sites->get($event['site']);
        $env = $site->getEnvironments()->get($event['env']);

        if ($env->get('connection_mode') == $event['mode']) {
            return ['env' => $event['env'], 'status' => 'succeeded', 'workflow' => 'The connection mode is already set to ' . $event['mode'] . '.'];
        }

        $workflow = $this->processWorkflow($env->changeConnectionMode($event['mode']));

        return $workflow->serialize();
    }
}
