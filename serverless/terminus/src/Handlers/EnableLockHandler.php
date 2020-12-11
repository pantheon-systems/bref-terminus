<?php declare(strict_types=1);

namespace Pantheon\Autopilot\Terminus\Handlers;

use Pantheon\Autopilot\Terminus\Traits\WorkflowProcessingTrait;

/**
 * Class EnableLockHandler
 * @package Pantheon\Autopilot\Terminus\Handlers
 */
class EnableLockHandler extends AbstractHandler
{
    use WorkflowProcessingTrait;

    /**
     * {@inheritdoc}
     *
     * The $event array must contain:
     *
     *   - 'env': The name of the target environment.
     *   - 'password': The password for the lock.
     *   - 'site': The site name or site id of the target site.
     *   - 'username': The username for the lock.
     */
    public function validate(array $event): void
    {
        $this->checkRequiredEventParameters($event, ['env', 'password', 'site', 'username',]);
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
        $lock = $env->getLock();
        $workflow = $this->processWorkflow($lock->enable([
            'password' => $event['password'],
            'username' => $event['username'],
        ]));

        return $workflow->serialize();
    }
}
