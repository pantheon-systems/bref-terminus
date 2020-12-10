<?php declare(strict_types=1);

namespace Pantheon\Autopilot\Terminus\Tests\Integration;

use Pantheon\Autopilot\Terminus\Handlers\CreateEnvHandler;

/**
 * Class CreateEnvHandlerTest
 * @package Pantheon\Autopilot\Terminus\Tests\Integration
 */
class CreateEnvHandlerTest extends AbstractHandlerTestCase
{
    /**
     * {@inheritDoc}
     *
     * Ensures the environment to be created does not already exist.
     */
    public function setUp()
    {
        parent::setUp();
        $this->ensureEnvDoesNotExist(self::SITE_ID, self::TARGET_ENV_NAME);
    }

    /**
     * Exercises the environment-creation handler.
     */
    public function testCreateEnv(): void
    {
        $event = ['site' => self::SITE_ID, 'source' => self::DEV_ENV_NAME, 'target' => self::TARGET_ENV_NAME,];
        $handler = new CreateEnvHandler($this->api);
        $result = $handler->handle($event);
        $this->assertIsValidUuid($result);
    }
}
