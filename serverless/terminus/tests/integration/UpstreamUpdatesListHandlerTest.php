<?php declare(strict_types=1);

namespace Pantheon\Autopilot\Terminus\Tests\Integration;

use Pantheon\Autopilot\Terminus\Handlers\UpstreamUpdatesListHandler;
use Pantheon\Autopilot\Terminus\Exceptions\UpstreamIsCurrentException;

/**
 * Class UpstreamUpdatesListHandlerTest
 * @package Pantheon\Autopilot\Terminus\Tests\Integration
 */
class UpstreamUpdatesListHandlerTest extends AbstractHandlerTestCase
{
    const OUTDATED_ENV_NAME = 'us-outdated';

    /**
     * Exercises the upstream-updates-List handler on a site guaranteed to have no upstream commits.
     */
    public function testUpstreamUpdatesListIsCurrent(): void
    {
        $this->expectException(UpstreamIsCurrentException::class);
        $this->expectExceptionMessage(self::SITE_ID_EMPTY_UPSTREAM.' has no upstream updates available.');

        $event = ['env' => self::DEV_ENV_NAME, 'site' => self::SITE_ID_EMPTY_UPSTREAM,];
        $handler = new UpstreamUpdatesListHandler($this->api);
        $result = $handler->handle($event);
    }

    /**
     * Exercises the upstream-updates-List handler on a site guaranteed to have upstream commits.
     */
    public function testUpstreamUpdatesList(): void
    {
        $event = ['env' => self::OUTDATED_ENV_NAME, 'site' => self::SITE_ID,];
        $handler = new UpstreamUpdatesListHandler($this->api);
        $result = $handler->handle($event);
        // There may be other updates listed in addition to 8.9.1, but since all
        // available updates are listed, this one should always be present.
        $regexp = '#Update to Drupal 8.9.1.#';

        $this->assertRegExp($regexp, json_encode($result));
    }
}
