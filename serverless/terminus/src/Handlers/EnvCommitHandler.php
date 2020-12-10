<?php declare(strict_types=1);

namespace Pantheon\Autopilot\Terminus\Handlers;

use Pantheon\Autopilot\Terminus\Exceptions\DiffstatIsEmptyException;
use Pantheon\Autopilot\Terminus\Traits\WorkflowProcessingTrait;

/**
 * Class EnvCommitHandler
 * @package Pantheon\Autopilot\Terminus\Handlers
 */
class EnvCommitHandler extends AbstractHandler
{
    use WorkflowProcessingTrait;

    /**
     * {@inheritdoc}
     *
     * The $event array must contain:
     *
     *   - 'env': The name of the source environment.
     *   - 'site': The site name or site id of the target site.
     *   - 'message': The comment to add to the commit.
     */
    public function validate(array $event): void
    {
        $this->checkRequiredEventParameters($event, ['env', 'site', 'message',]);
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

        // If the connection mode is not sftp, then there will never
        // be anything to commit. While we could provide a more specific
        // error, this should never be needed. Sfns that commit should
        // set sftp mode prior to making changes & committing.
        if (($env->get('connection_mode') !== 'sftp') ||
             (count((array)$env->diffstat()) == 0)) {
            throw new DiffstatIsEmptyException("Nothing to commit.");
        }

        $workflow = $this->processWorkflow($env->commitChanges($event['message']));

        return $workflow->serialize();
    }
}
