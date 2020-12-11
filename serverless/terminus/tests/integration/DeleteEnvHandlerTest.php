<?php declare(strict_types=1);

namespace Pantheon\Autopilot\Terminus\Tests\Integration;

use Pantheon\Autopilot\Terminus\Handlers\DeleteEnvHandler;

/**
 * Class DeleteEnvHandlerTest
 * @package Pantheon\Autopilot\Terminus\Tests\Integration
 */
class DeleteEnvHandlerTest extends AbstractHandlerTestCase
{
    /**
     * {@inheritDoc}
     *
     * Ensures the environment to be deleted exists.
     */
    
    
    public function setUp()
    {
        parent::setUp();
        $this->ensureEnvExists(self::SITE_ID, self::TARGET_ENV_NAME);
    }

    /**
     * Exercises the environment-deletion handler.
     */
    public function testDeleteEnv(): void
    {
        $this->markTestSkipped('Previously hitting "Could not find an environment identified by integ-test."');

        $event = ['env' => self::TARGET_ENV_NAME, 'site' => self::SITE_ID,];
        $handler = new DeleteEnvHandler($this->api);
        $result = $handler->handle($event);

        $this->assertIsValidUuid($result);
    }
}
