<?php declare(strict_types=1);

namespace Pantheon\Autopilot\Terminus\Tests\Integration;

use Pantheon\Autopilot\Terminus\Handlers\CloneDatabaseHandler;

/**
 * Class CloneDatabaseHandlerTest
 * @package Pantheon\Autopilot\Terminus\Tests\Integration
 */
class CloneDatabaseHandlerTest extends AbstractHandlerTestCase
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
     * Exercises the database-cloning handler.
     */
    public function testCloneDatabase(): void
    {
        $event = ['site' => self::SITE_ID, 'source' => self::DEV_ENV_NAME, 'target' => self::TARGET_ENV_NAME,];
        $handler = new CloneDatabaseHandler($this->api);
        $result = $handler->handle($event);

        $this->assertIsValidUuid($result);
    }
}
