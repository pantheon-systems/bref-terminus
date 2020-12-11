<?php declare(strict_types=1);

namespace Pantheon\Autopilot\Terminus\Handlers;

/**
 * Class DeployEnvHandler
 * @package Pantheon\Autopilot\Terminus\Handlers
 */
class DeployEnvHandler extends AbstractHandler
{
    /**
     * {@inheritdoc}
     *
     * The $event array must contain:
     *
     *   - 'env': The name of the source environment.
     *   - 'site': The site name or site id of the target site.
     *   - 'annotation': The deployment message
     */
    public function validate(array $event): void
    {
        $this->checkRequiredEventParameters($event, ['env', 'site', 'annotation']);
    }

    /**
     * {@inheritdoc}
     *
     * @see env:deploy for expected parameters in $event array.
     *
     * @return string
     * @throws \Pantheon\Terminus\Exceptions\TerminusException
     * @throws \Pantheon\Terminus\Exceptions\TerminusNotFoundException
     */
    public function action(array $event): string
    {
        $sites = $this->api->sites();
        $site = $sites->get($event['site']);
        $env = $site->getEnvironments()->get($event['env']);
        $params = [
            'annotation' => $event['annotation'],
            'updatedb' => true,
        ];
        $workflow = $env->deploy($params);

        return $workflow->id;
    }
}
