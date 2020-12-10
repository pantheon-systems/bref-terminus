<?php declare(strict_types=1);

namespace Pantheon\Autopilot\Terminus\Handlers;

/**
 * Class MergeToDevHandler
 * @package Pantheon\Autopilot\Terminus\Handlers
 */
class MergeToDevHandler extends AbstractHandler
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
     * @see env:merge-to-dev for expected parameters in $event array.
     *
     * @return string
     * @throws \Pantheon\Terminus\Exceptions\TerminusException
     * @throws \Pantheon\Terminus\Exceptions\TerminusNotFoundException
     */
    public function action(array $event): string
    {
        $sites = $this->api->sites();
        $site = $sites->get($event['site']);
        $dev_env = $site->getEnvironments()->get('dev');
        $workflow = $dev_env->mergeToDev(['from_environment' => $event['env'],]);

        return $workflow->id;
    }
}
