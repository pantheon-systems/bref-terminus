<?php declare(strict_types=1);

namespace Pantheon\Autopilot\Terminus\Handlers;

/**
 * Class ApplyUpstreamUpdatesHandler
 * @package Pantheon\Autopilot\Terminus\Handlers
 */
class ApplyUpstreamUpdatesHandler extends AbstractHandler
{
    /**
     * {@inheritdoc}
     *
     * The $event array must contain:
     *
     *   - 'env': The name of the source environment.
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
     */
    public function action(array $event): string
    {
        $sites = $this->api->sites();
        $site = $sites->get($event['site']);
        $env = $site->getEnvironments()->get($event['env']);
        $workflow = $env->applyUpstreamUpdates();

        return $workflow->id;
    }
}
