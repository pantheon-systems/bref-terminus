<?php declare(strict_types=1);

namespace Pantheon\Autopilot\Terminus\Handlers;

use Pantheon\Autopilot\Terminus\Exceptions\DiffstatIsEmptyException;

/**
 * Class EnvDiffstatHandler
 * @package Pantheon\Autopilot\Terminus\Handlers
 */
class EnvDiffstatHandler extends AbstractHandler
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

        $diffstat = json_decode(json_encode($env->diffstat()), true);

        if (empty($diffstat)) {
            throw new DiffstatIsEmptyException("The env diffstat is empty.");
        }

        return $diffstat;
    }
}
