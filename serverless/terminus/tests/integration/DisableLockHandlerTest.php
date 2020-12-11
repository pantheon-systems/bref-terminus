<?php declare(strict_types=1);

namespace Pantheon\Autopilot\Terminus\Tests\Integration;

use Pantheon\Autopilot\Terminus\Handlers\DisableLockHandler;

/**
 * Class DisableLockHandlerTest
 * @package Pantheon\Autopilot\Terminus\Tests\Integration
 */
class DisableLockHandlerTest extends AbstractHandlerTestCase
{
    /**
     * Ensures the given environment is locked
     *
     * @param string $site_id
     * @param string $env_name
     * @throws TerminusException
     * @throws TerminusNotFoundException
     */
    protected function ensureEnvIsLocked($site_id, $env_name)
    {
        $env = $this->getEnvironments($site_id)->get($env_name);

        if (!$env->getLock()->isLocked()) {
            $env->getLock()->enable([
                'password' => self::LOCK_PASSWORD,
                'username' => self::LOCK_USERNAME,
            ]);
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
        $this->ensureEnvIsLocked(self::SITE_ID, self::DEV_ENV_NAME);
    }

    /**
     * Exercises the disable-lock handler.
     */
    public function testDisableLock(): void
    {
        $event = ['env' => self::DEV_ENV_NAME, 'site' => self::SITE_ID,];
        $handler = new DisableLockHandler($this->api);
        $result = $handler->handle($event);

        $this->assertEquals('succeeded', $result['status']);
    }
}
