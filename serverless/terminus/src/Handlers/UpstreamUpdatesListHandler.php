<?php declare(strict_types=1);

namespace Pantheon\Autopilot\Terminus\Handlers;

use Pantheon\Autopilot\Terminus\Exceptions\UpstreamIsCurrentException;

/**
 * Class UpstreamUpdatesListHandler
 * @package Pantheon\Autopilot\Terminus\Handlers
 */
class UpstreamUpdatesListHandler extends AbstractHandler
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
     * @return object
     * @throws \Pantheon\Terminus\Exceptions\TerminusException
     * @throws \Pantheon\Terminus\Exceptions\TerminusNotFoundException
     * @throws \Pantheon\Terminus\Exceptions\UpstreamIsCurrentException
     */
    public function action(array $event): array
    {
        $sites = $this->api->sites();
        $site = $sites->get($event['site']);
        $env = $site->getEnvironments()->get($event['env']);
        $updates = $this->serializeAndFormatUpdates($env);

        if (empty($updates)) {
            throw new UpstreamIsCurrentException($event['site']." has no upstream updates available.");
        }

        return $updates;
    }

    /**
     * Return the upstream for the given site
     *
     * @param Site $site
     * @return object The upstream information
     * @throws TerminusException
     */
    protected function getUpstreamUpdates($env)
    {
        if (empty($upstream = $env->getUpstreamStatus()->getUpdates())) {
            throw new TerminusException('There was a problem checking your upstream status. Please try again.');
        }
        return $upstream;
    }

    /**
     * Get the list of upstream updates for a site
     *
     * @param Site $site
     * @return array The list of updates
     * @throws TerminusException
     */
    protected function getUpstreamUpdatesLog($env)
    {
        $updates = $this->getUpstreamUpdates($env);
        return property_exists($updates, 'update_log') ? (array)$updates->update_log : [];
    }

    protected function serializeAndFormatUpdates($env)
    {
        $data = [];
        foreach ($this->getUpstreamUpdatesLog($env) as $commit) {
            $clean_message = preg_replace(['/[;"\|\'\^`]/', '/[\r\n]/'], ['', ' '], $commit->message);

            $data[] = [
                'hash' => $commit->hash,
                'datetime' => $commit->datetime,
                'message' => trim($clean_message)
            ];
        }

        usort(
            $data,
            function ($a, $b) {
                if (strtotime($a['datetime']) === strtotime($b['datetime'])) {
                    return 0;
                }
                return (strtotime($a['datetime']) > strtotime($b['datetime'])) ? -1 : 1;
            }
        );

        // Return the output data.
        return array_reverse($data);
    }
}
