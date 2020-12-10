<?php declare(strict_types=1);

namespace Pantheon\Autopilot\Terminus\Tests\Integration;

use Pantheon\Autopilot\Terminus\Handlers\ClearEnvCacheHandler;

/**
 * Class ClearEnvCacheHandlerTest
 * @package Pantheon\Autopilot\Terminus\Tests\Integration
 */
class ClearEnvCacheHandlerTest extends AbstractHandlerTestCase
{
    /**
     * Exercises the clear-env-cache handler.
     */
    public function testClearEnvCache(): void
    {
        $event = ['env' => self::DEV_ENV_NAME, 'site' => self::SITE_ID,];
        $handler = new ClearEnvCacheHandler($this->api);
        $result = $handler->handle($event);

        $this->assertEquals('succeeded', $result['status']);
    }
}
