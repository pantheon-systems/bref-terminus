<?php declare(strict_types=1);

namespace Pantheon\Autopilot\Terminus\Tests\Integration;

use Pantheon\Autopilot\Terminus\Handlers\MultidevListHandler;

/**
 * Class MultidevListHandlerTest
 *
 * @package Pantheon\Autopilot\Terminus\Tests\Integration
 */
class MultidevListHandlerTest extends AbstractHandlerTestCase
{
    /**
     * {@inheritDoc}
     *
     * Ensures the multidev environment being cloned into exists.
     */
    public function setUp()
    {
        parent::setUp();
        $this->ensureEnvExists(self::SITE_ID, self::TARGET_ENV_NAME);
    }

    /**
     * Exercises the multidev list handler.
     */
    public function testMultidevList(): void
    {

        $event = ['site' => self::SITE_ID,];
        $handler = new MultidevListHandler($this->api);
        $result = $handler->handle($event);
        $multidev_key = self::TARGET_ENV_NAME;

        $this->assertArrayHasKey($multidev_key, $result);
    }
}
