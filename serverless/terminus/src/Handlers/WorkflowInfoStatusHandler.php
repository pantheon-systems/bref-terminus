<?php declare(strict_types=1);

namespace Pantheon\Autopilot\Terminus\Handlers;

use Pantheon\Autopilot\Terminus\Exceptions\ErredWorkflowException;
use Pantheon\Autopilot\Terminus\Exceptions\InvalidUuidException;
use Pantheon\Autopilot\Terminus\Exceptions\RunningWorkflowException;

/**
 * Class WorkflowInfoStatusHandler
 * @package Pantheon\Autopilot\Terminus\Handlers
 */
class WorkflowInfoStatusHandler extends AbstractHandler
{
    /**
     * {@inheritdoc}
     *
     * The $event array must contain:
     *
     *   - 'site': The site name or site id of the target site.
     *   - 'workflow': The UUID of the workflow to check
     *
     * @throws InvalidUuidException
     */
    public function validate(array $event): void
    {
        $this->checkRequiredEventParameters($event, ['site', 'workflow',]);
        $workflow_id = $event['workflow'];
        if (!InvalidUuidException::isValidUuid($workflow_id)) {
            throw new InvalidUuidException('workflow', $workflow_id);
        }
    }

    /**
     * {@inheritdoc}
     *
     * @return array Only if the workflow has succeeded.
     * @throws ErredWorkflowException If the workflow has erred.
     * @throws RunningWorkflowException If the workflow is still running.
     * @throws \Pantheon\Terminus\Exceptions\TerminusException
     *
     * @see workflow:info:status for expected parameters in $event array.
     */
    public function action(array $event): array
    {
        $sites = $this->api->sites();
        $site = $sites->get($event['site']);
        $workflow = $site->getWorkflows()->get($event['workflow'])->fetch();
        if (!$workflow->isFinished()) {
            throw new RunningWorkflowException($workflow);
        }
        if (!$workflow->isSuccessful()) {
            throw new ErredWorkflowException($workflow);
        }

        return $workflow->serialize();
    }
}
