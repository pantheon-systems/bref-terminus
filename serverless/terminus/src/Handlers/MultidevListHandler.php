<?php declare(strict_types=1);

namespace Pantheon\Autopilot\Terminus\Handlers;

/**
 * Class MultidevListHandler
 * @package Pantheon\Autopilot\Terminus\Handlers
 */
class MultidevListHandler extends AbstractHandler
{
    /**
     * {@inheritdoc}
     *
     * The $event array must contain:
     *
     *   - 'site': The site name or site id of the target site.
     */
    public function validate(array $event): void
    {
        $this->checkRequiredEventParameters($event, ['site']);
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
        $multidevs = $site->getEnvironments()->filterForMultidev();

        return $multidevs->serialize();
    }
}
