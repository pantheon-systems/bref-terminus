<?php declare(strict_types=1);

namespace Pantheon\Autopilot\Terminus\Tests\Integration;

use Pantheon\Autopilot\Terminus\Exceptions\ErredWorkflowException;
use Pantheon\Autopilot\Terminus\Handlers\WorkflowInfoStatusHandler;
use Pantheon\Terminus\Models\Workflow;

/**
 * Class WorkflowInfoStatusHandlerTest
 * @package Pantheon\Autopilot\Terminus\Tests\Integration
 */
class WorkflowInfoStatusHandlerTest extends AbstractHandlerTestCase
{
    /**
     * @var Workflow
     */
    private $workflow;

    /**
     * {@inheritDoc}
     *
     * Starts a workflow
     */
    public function setUp()
    {
        parent::setUp();
        $this->workflow = $this->startWorkflow();
    }

    /**
     * Exercises the site information-retrieval handler.
     */
    public function testWorkflowInfoStatus(): void
    {
        $this->expectException(ErredWorkflowException::class);
        $event = ['site' => self::SITE_ID, 'workflow' => $this->workflow->id,];
        $handler = new WorkflowInfoStatusHandler($this->api);
        $handler->handle($event);
    }

    /**
     * Starts a workflow which is known to result in an exception from the handler because its params are wrong.
     *
     * @throws \Pantheon\Terminus\Exceptions\TerminusException
     */
    private function startWorkflow(): Workflow
    {
        return $this->api->sites()->get(self::SITE_ID)->deployProduct('invalid');
    }
}
