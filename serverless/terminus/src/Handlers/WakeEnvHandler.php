<?php declare(strict_types=1);

namespace Pantheon\Autopilot\Terminus\Handlers;

/**
 * Class WakeEnvHandler
 * @package Pantheon\Autopilot\Terminus\Handlers
 */
class WakeEnvHandler extends AbstractHandler
{
    /**
     * {@inheritdoc}
     *
     * The $event array must contain:
     *
     *   - 'env': The name of the target environment.
     *   - 'site': The site name or site id of the target site.
     */
    public function validate(array $event): void
    {
        $this->checkRequiredEventParameters($event, ['env', 'site',]);
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

        return $env->wake();
    }
}
