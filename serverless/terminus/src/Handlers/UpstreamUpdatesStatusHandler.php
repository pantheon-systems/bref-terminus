<?php declare(strict_types=1);

namespace Pantheon\Autopilot\Terminus\Handlers;

use Pantheon\Autopilot\Terminus\Exceptions\UpstreamIsCurrentException;

/**
 * Class UpstreamUpdatesStatusHandler
 * @package Pantheon\Autopilot\Terminus\Handlers
 */
class UpstreamUpdatesStatusHandler extends AbstractHandler
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
        $this->checkRequiredEventParameters($event, ['env', 'site']);
    }

    /**
     * {@inheritdoc}
     *
     * @see self::validate for expected parameters in $event array.
     *
     * @return string
     * @throws \Pantheon\Terminus\Exceptions\TerminusException
     * @throws \Pantheon\Terminus\Exceptions\TerminusNotFoundException
     * @throws \Pantheon\Terminus\Exceptions\UpstreamIsCurrentException
     */
    public function action(array $event): string
    {
        $sites = $this->api->sites();
        $site = $sites->get($event['site']);
        $env = $site->getEnvironments()->get($event['env']);

        $status = $env->getUpstreamStatus()->getStatus();
        if ($status == 'current') {
            throw new UpstreamIsCurrentException($event['site']." has no upstream updates available.");
        }

        return $status;
    }
}
