<?php declare(strict_types=1);

namespace Pantheon\Autopilot\Terminus\Exceptions;

/**
 * Class ErredWorkflowException
 * @package Pantheon\Autopilot\Terminus\Exceptions
 */
class ErredWorkflowException extends AbstractWorkflowException
{
    const MESSAGE = 'Workflow ' . self::WORKFLOW_MESSAGE_KEY . ' has failed.';
}
