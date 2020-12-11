<?php declare(strict_types=1);

namespace Pantheon\Autopilot\Terminus\Exceptions;

use Pantheon\Terminus\Models\Workflow;

/**
 * Class AbstractWorkflowException
 * @package Pantheon\Autopilot\Terminus\Exceptions
 */
abstract class AbstractWorkflowException extends \Exception
{
    const MESSAGE = '';
    const WORKFLOW_MESSAGE_KEY = '{workflow_id}';

    /**
     * Accepts a Workflow as its argument and interpolates it into the exception message.
     *
     * @param Workflow $workflow Object representing the workflow started by lambda.
     */
    public function __construct(Workflow $workflow)
    {
        parent::__construct(str_replace(self::WORKFLOW_MESSAGE_KEY, $workflow->id, self::MESSAGE));
    }
}
