<?php declare(strict_types=1);

namespace Pantheon\Autopilot\Terminus\Tests\Integration;

use Pantheon\Autopilot\Terminus\Handlers\SetConnectionHandler;
use Pantheon\Terminus\Exceptions\TerminusException;

/**
 * Class SetConnectionHandlerTest
 *
 * @package Pantheon\Autopilot\Terminus\Tests\Integration
 */
class SetConnectionHandlerTest extends AbstractHandlerTestCase
{
    /**
     * Ensures the environment's connection is of the intended mode
     *
     * @param string $site_id
     * @param string $env_name
     * @param string $$intended_mode
     * @throws TerminusException
     */
    protected function ensureConnectionMode($site_id, $env_name, $intended_mode): void
    {
        $env = $this->getEnvironments($site_id)->get($env_name);
        $mode = $env->serialize()['connection_mode'];

        if ($mode != $intended_mode) {
            $env->changeConnectionMode($intended_mode);
        }
    }

    /**
     * Exercises the connection-mode-setting handler.
     */
    public function testSetConnectionGitToGit(): void
    {
        $initial_mode = 'git';
        $intended_mode = 'git';
        $this->ensureConnectionMode(self::SITE_ID, self::TARGET_ENV_NAME, $initial_mode);

        $event = ['env' => self::TARGET_ENV_NAME, 'mode' => $intended_mode, 'site' => self::SITE_ID,];
        $handler = new SetConnectionHandler($this->api);
        $result = $handler->handle($event);

        $this->assertEquals('succeeded', $result['status']);
    }

    /**
     * Exercises the connection-mode-setting handler.
     */
    public function testSetConnectionGitToSftp(): void
    {
        $initial_mode = 'git';
        $intended_mode = 'sftp';
        $this->ensureConnectionMode(self::SITE_ID, self::TARGET_ENV_NAME, $initial_mode);

        $event = ['env' => self::TARGET_ENV_NAME, 'mode' => $intended_mode, 'site' => self::SITE_ID,];
        $handler = new SetConnectionHandler($this->api);
        $result = $handler->handle($event);

        $this->assertEquals('succeeded', $result['status']);
    }

    /**
     * Exercises the connection-mode-setting handler.
     */
    public function testSetConnectionSftpToSftp(): void
    {
        $initial_mode = 'sftp';
        $intended_mode = 'sftp';
        $this->ensureConnectionMode(self::SITE_ID, self::TARGET_ENV_NAME, $initial_mode);

        $event = ['env' => self::TARGET_ENV_NAME, 'mode' => $intended_mode, 'site' => self::SITE_ID,];
        $handler = new SetConnectionHandler($this->api);
        $result = $handler->handle($event);

        $this->assertEquals('succeeded', $result['status']);
    }

    /**
     * Exercises the connection-mode-setting handler.
     */
    public function testSetConnectionSftpToGit(): void
    {
        $initial_mode = 'sftp';
        $intended_mode = 'git';
        $this->ensureConnectionMode(self::SITE_ID, self::TARGET_ENV_NAME, $initial_mode);

        $event = ['env' => self::TARGET_ENV_NAME, 'mode' => $intended_mode, 'site' => self::SITE_ID,];
        $handler = new SetConnectionHandler($this->api);
        $result = $handler->handle($event);

        $this->assertEquals('succeeded', $result['status']);
    }
}
