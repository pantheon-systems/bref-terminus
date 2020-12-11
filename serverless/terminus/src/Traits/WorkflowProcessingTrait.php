<?php declare(strict_types=1);

namespace Pantheon\Autopilot\Terminus\Traits;

use Pantheon\Terminus\Models\Workflow;

/**
 * Class WorkflowTrait
 * @package Pantheon\Autopilot\Terminus\Traits
 */
trait WorkflowProcessingTrait
{
    /**
     * @param Workflow $model A workflow to run
     * @return Workflow That same workflow
     */
    public function processWorkflow(Workflow $workflow)
    {
        do {
            $workflow->fetch();
            usleep(10 * 1000);
        } while (!$workflow->isFinished());

        return $workflow;
    }
}
