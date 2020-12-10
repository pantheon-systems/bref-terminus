<?php declare(strict_types=1);

namespace Pantheon\Autopilot\Terminus\Handlers;

/**
 * Class CloneFilesHandler
 * @package Pantheon\Autopilot\Terminus\Handlers
 */
class CloneFilesHandler extends AbstractHandler
{
    /**
     * {@inheritdoc}
     *
     * The $event array must contain:
     *
     *   - 'site': The site name or site id of the target site.
     *   - 'source': The name of the source environment.
     *   - 'target': The name of the target environment.
     */
    public function validate(array $event): void
    {
        $this->checkRequiredEventParameters($event, ['site', 'source', 'target',]);
    }

    /**
     * {@inheritdoc}
     *
     * @see env:clone-content --files-only for expected parameters in $event array.
     *
     * @return string
     * @throws \Pantheon\Terminus\Exceptions\TerminusException
     * @throws \Pantheon\Terminus\Exceptions\TerminusNotFoundException
     */
    public function action(array $event): string
    {
        $sites = $this->api->sites();
        $site = $sites->get($event['site']);
        $environments = $site->getEnvironments();
        $source_env = $environments->get($event['source']);
        $target_env = $environments->get($event['target']);
        $workflow = $target_env->cloneFiles($source_env);

        return $workflow->id;
    }
}
