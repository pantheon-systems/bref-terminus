<?php declare(strict_types=1);

namespace Pantheon\Autopilot\Terminus\Handlers;

/**
 * Class SiteInfoHandler
 * @package Pantheon\Autopilot\Terminus\Handlers
 */
class SiteInfoHandler extends AbstractHandler
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
     */
    public function action(array $event): array
    {
        $sites = $this->api->sites();
        $site = $sites->get($event['site']);

        return $site->serialize();
    }
}
