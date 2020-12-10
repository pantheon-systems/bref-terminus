<?php declare(strict_types=1);

namespace Pantheon\Autopilot\Terminus\Handlers;

/**
 * Class ConnectionInfoHandler
 * @package Pantheon\Autopilot\Terminus\Handlers
 */
class ConnectionInfoHandler extends AbstractHandler
{
    /**
     * {@inheritdoc}
     *
     * The $event array must contain:
     *
     *   - 'site': The site name or site id of the target site.
     *   - 'env': The name of the target environment.
     */
    public function validate(array $event): void
    {
        $this->checkRequiredEventParameters($event, ['site', 'env']);
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

        return $env->connectionInfo();
    }
}
