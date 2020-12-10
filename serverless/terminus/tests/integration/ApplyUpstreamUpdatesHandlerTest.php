<?php declare(strict_types=1);

namespace Pantheon\Autopilot\Terminus\Tests\Integration;

use Pantheon\Autopilot\Terminus\Handlers\ApplyUpstreamUpdatesHandler;

/**
 * Class ApplyUpstreamUpdatesHandlerTest
 * @package Pantheon\Autopilot\Terminus\Tests\Integration
 */
class ApplyUpstreamUpdatesHandlerTest extends AbstractHandlerTestCase
{
    /**
     * Exercises the apply-upstream-updates handler.
     */
    public function testApplyUpstreamUpdates(): void
    {
        $event = ['env' => self::DEV_ENV_NAME, 'site' => self::SITE_ID,];
        $handler = new ApplyUpstreamUpdatesHandler($this->api);
        $result = $handler->handle($event);

        $this->assertIsValidUuid($result);
    }
}
