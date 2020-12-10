<?php declare(strict_types=1);

namespace Pantheon\Autopilot\Terminus\Tests\Integration;

use Pantheon\Autopilot\Terminus\Handlers\MergeToDevHandler;

/**
 * Class MergeToDevHandlerTest
 * @package Pantheon\Autopilot\Terminus\Tests\Integration
 */
class MergeToDevHandlerTest extends AbstractHandlerTestCase
{
    /**
     * {@inheritDoc}
     *
     * Ensures the environment being merged into dev exists.
     */
    public function setUp()
    {
        parent::setUp();
        $this->ensureEnvExists(self::SITE_ID, self::TARGET_ENV_NAME);
    }

    /**
     * Exercises the merge-to-dev handler.
     */
    public function testMergeToDev(): void
    {
        $event = ['env' => self::TARGET_ENV_NAME, 'site' => self::SITE_ID,];
        $handler = new MergeToDevHandler($this->api);
        $result = $handler->handle($event);

        $this->assertIsValidUuid($result);
    }
}
