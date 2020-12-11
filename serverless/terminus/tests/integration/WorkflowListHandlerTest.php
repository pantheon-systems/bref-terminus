<?php declare(strict_types=1);

namespace Pantheon\Autopilot\Terminus\Tests\Integration;

use Pantheon\Autopilot\Terminus\Handlers\WorkflowListHandler;

/**
 * Class WorkflowListHandlerTest
 *
 * @package Pantheon\Autopilot\Terminus\Tests\Integration
 */
class WorkflowListHandlerTest extends AbstractHandlerTestCase
{
    /**
     * Exercises the workflow list handler.
     */
    public function testWorkflowList(): void
    {

        $event = ['site' => self::SITE_ID,];
        $handler = new WorkflowListHandler($this->api);
        $result = $handler->handle($event);

        $this->assertNotEmpty($result);
    }
}
