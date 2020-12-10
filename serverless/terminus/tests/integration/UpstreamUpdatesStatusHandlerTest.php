<?php declare(strict_types=1);

namespace Pantheon\Autopilot\Terminus\Tests\Integration;

use Pantheon\Autopilot\Terminus\Handlers\UpstreamUpdatesStatusHandler;
use Pantheon\Autopilot\Terminus\Exceptions\UpstreamIsCurrentException;

/**
 * Class UpstreamUpdatesStatusHandlerTest
 * @package Pantheon\Autopilot\Terminus\Tests\Integration
 */
class UpstreamUpdatesStatusHandlerTest extends AbstractHandlerTestCase
{
    const OUTDATED_ENV_NAME = 'us-outdated';

    /**
     * Exercises the upstream-updates-status handler on a site guaranteed to have no upstream commits.
     */
    public function testUpstreamUpdatesStatusIsCurrent(): void
    {
        $this->expectException(UpstreamIsCurrentException::class);
        $this->expectExceptionMessage(self::SITE_ID_EMPTY_UPSTREAM.' has no upstream updates available.');

        $event = ['env' => self::DEV_ENV_NAME, 'site' => self::SITE_ID_EMPTY_UPSTREAM,];
        $handler = new UpstreamUpdatesStatusHandler($this->api);
        $result = $handler->handle($event);
    }

    /**
     * Exercises the upstream-updates-status handler on a site guaranteed to have upstream commits.
     */
    public function testUpstreamUpdatesStatus(): void
    {
        $event = ['env' => self::OUTDATED_ENV_NAME, 'site' => self::SITE_ID,];
        $handler = new UpstreamUpdatesStatusHandler($this->api);
        $result = $handler->handle($event);
        $regexp = '#outdated#';

        $this->assertRegExp($regexp, $result);
    }
}
