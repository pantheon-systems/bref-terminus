<?php declare(strict_types=1);

namespace Pantheon\Autopilot\Terminus\Tests\Integration;

use Pantheon\Autopilot\Terminus\Handlers\LockInfoHandler;

/**
 * Class LockInfoHandlerTest
 * @package Pantheon\Autopilot\Terminus\Tests\Integration
 */
class LockInfoHandlerTest extends AbstractHandlerTestCase
{
    /**
     * Exercises the lock-information-retrieval handler.
     */
    public function testLockInfo(): void
    {
        $event = ['env' => self::DEV_ENV_NAME, 'site' => self::SITE_ID,];
        $handler = new LockInfoHandler($this->api);
        $result = $handler->handle($event);

        $this->assertArrayHasKey('locked', $result);
    }
}
