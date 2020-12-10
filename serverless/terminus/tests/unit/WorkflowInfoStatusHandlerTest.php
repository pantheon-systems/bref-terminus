<?php declare(strict_types=1);

namespace Pantheon\Autopilot\Terminus\Tests\Unit;

use Pantheon\Autopilot\Terminus\Exceptions\ErredWorkflowException;
use Pantheon\Autopilot\Terminus\Exceptions\InvalidUuidException;
use Pantheon\Autopilot\Terminus\Exceptions\RunningWorkflowException;
use Pantheon\Autopilot\Terminus\Handlers\WorkflowInfoStatusHandler;

/**
 * Class WorkflowInfoStatusHandlerTest
 * @package Pantheon\Autopilot\Terminus\Tests\Unit
 */
class WorkflowInfoStatusHandlerTest extends AbstractHandlerTestCase
{
    /**
     * @var WorkflowInfoStatusHandler
     */
    protected $handler;

    /**
     * {@inheritDoc}
     *
     * Creates the handler and gives it the mock API object
     */
    public function setUp()
    {
        parent::setUp();
        $this->handler = new WorkflowInfoStatusHandler($this->api);
        $this->workflow->method('fetch')
            ->willReturn($this->workflow);
    }

    /**
     * Tests the error condition of workflow status check
     *
     * @throws ErredWorkflowException
     * @throws \Pantheon\Autopilot\Terminus\Exceptions\RunningAbstractWorkflowException
     * @throws \Pantheon\Autopilot\Terminus\Exceptions\RunningWorkflowException
     * @throws \Pantheon\Terminus\Exceptions\TerminusException
     */
    public function testActionErred(): void
    {
        $this->expectException(ErredWorkflowException::class);

        $this->workflow->method('isFinished')->willReturn(true);
        $this->workflow->method('isSuccessful')->willReturn(false);

        $this->handler->action(['site' => $this->site->id, 'workflow' => $this->workflow->id,]);
    }

    /**
     * Tests the unfinished condition of workflow status check
     *
     * @throws RunningWorkflowException
     * @throws \Pantheon\Autopilot\Terminus\Exceptions\RunningAbstractWorkflowException
     * @throws \Pantheon\Autopilot\Terminus\Exceptions\RunningWorkflowException
     * @throws \Pantheon\Terminus\Exceptions\TerminusException
     */
    public function testActionRunning(): void
    {
        $this->expectException(RunningWorkflowException::class);

        $this->workflow->method('isFinished')->willReturn(false);
        $this->workflow->method('isSuccessful')->willReturn(null);

        $this->handler->action(['site' => $this->site->id, 'workflow' => $this->workflow->id,]);
    }

    /**
     * Tests the success condition of workflow status check
     *
     * @throws \Pantheon\Autopilot\Terminus\Exceptions\RunningAbstractWorkflowException
     * @throws \Pantheon\Autopilot\Terminus\Exceptions\RunningWorkflowException
     * @throws \Pantheon\Terminus\Exceptions\TerminusException
     */
    public function testActionSuccess(): void
    {
        $workflow_data = ['id' => $this->workflow->id, 'status' => 'succeeded',];

        $this->workflow->method('isFinished')->willReturn(true);
        $this->workflow->method('isSuccessful')->willReturn(true);
        $this->workflow->method('serialize')->willReturn($workflow_data);

        $out = $this->handler->action(['site' => $this->site->id, 'workflow' => $this->workflow->id,]);
        $this->assertEquals($workflow_data, $out);
    }

    /**
     * Tests the unsuccessful evaluation of a UUID
     */
    public function testInvalidateUuid(): void
    {
        $this->expectException(InvalidUuidException::class);
        $this->handler->validate(['site' => $this->site->id, 'workflow' => 'invalid af',]);
    }

    /**
     * Tests the successful evaluation of a UUID
     */
    public function testValidateUuid(): void
    {
        $out = $this->handler->validate(['site' => $this->site->id, 'workflow' => $this->workflow->id,]);
        $this->assertNull($out);
    }
}
