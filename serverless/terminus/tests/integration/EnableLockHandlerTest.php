<?php declare(strict_types=1);

namespace Pantheon\Autopilot\Terminus\Tests\Integration;

use Pantheon\Autopilot\Terminus\Handlers\EnableLockHandler;

/**
 * Class EnableLockHandlerTest
 * @package Pantheon\Autopilot\Terminus\Tests\Integration
 */
class EnableLockHandlerTest extends AbstractHandlerTestCase
{
    /**
     * Ensures the given environment is locked
     *
     * @param string $site_id
     * @param string $env_name
     * @throws TerminusException
     * @throws TerminusNotFoundException
     */
    protected function ensureEnvIsUnlocked($site_id, $env_name)
    {
        $env = $this->getEnvironments($site_id)->get($env_name);

        if ($env->getLock()->isLocked()) {
            $env->getLock()->disable();
        }
    }

    /**
     * {@inheritDoc}
     *
     * Ensures the environment to be unlocked is locked.
     */
    public function setUp()
    {
        parent::setUp();
        $this->ensureEnvIsUnlocked(self::SITE_ID, self::DEV_ENV_NAME);
    }

    /**
     * {@inheritDoc}
     *
     * Ensures the environment to be unlocked is locked.
     */
    public function tearDown()
    {
        parent::tearDown();
        $this->ensureEnvIsUnlocked(self::SITE_ID, self::DEV_ENV_NAME);
    }

    /**
     * Exercises the enable-lock handler.
     */
    public function testEnableLock(): void
    {
        $event = ['env' => self::DEV_ENV_NAME, 'password' => self::LOCK_PASSWORD, 'site' => self::SITE_ID, 'username' => self::LOCK_USERNAME, ];
        $handler = new EnableLockHandler($this->api);
        $result = $handler->handle($event);

        $this->assertEquals('succeeded', $result['status']);
    }
}
