<?php declare(strict_types=1);

namespace Pantheon\Autopilot\Terminus\Tests\Integration;

use Pantheon\Autopilot\Terminus\Handlers\WakeEnvHandler;

/**
 * Class WakeEnvHandlerTest
 * @package Pantheon\Autopilot\Terminus\Tests\Integration
 */
class WakeEnvHandlerTest extends AbstractHandlerTestCase
{
    /**
     * Exercises the env-waking handler.
     */
    public function testWakeEnv(): void
    {
        $event = ['env' => self::DEV_ENV_NAME, 'site' => self::SITE_ID,];
        $handler = new WakeEnvHandler($this->api);
        $result = $handler->handle($event);

        $this->assertTrue($result['success']);
    }
}
