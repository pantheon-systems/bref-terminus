<?php declare(strict_types=1);

namespace Pantheon\Autopilot\Terminus\Exceptions;

/**
 * Class RunningWorkflowException
 * @package Pantheon\Autopilot\Terminus\Exceptions
 */
class RunningWorkflowException extends AbstractWorkflowException
{
    const MESSAGE = 'Workflow ' . self::WORKFLOW_MESSAGE_KEY . ' is running.';
}
