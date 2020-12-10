<?php declare(strict_types=1);

namespace Pantheon\Autopilot\Terminus\Tests\Integration;

use Pantheon\Autopilot\Terminus\Handlers\EnvCommitHandler;
use Pantheon\Autopilot\Terminus\Exceptions\DiffstatIsEmptyException;

/**
 * Class EnvDiffstatHandlerTest
 * @package Pantheon\Autopilot\Terminus\Tests\Integration
 */
class EnvCommitHandlerTest extends AbstractHandlerTestCase
{
    /**
     * Exercises the env-commit handler on an empty environment.
     */
    public function testEnvEmptyCommit(): void
    {
        $this->expectException(DiffstatIsEmptyException::class);
        $this->expectExceptionMessage('Nothing to commit.');

        $event = ['env' => self::DEV_ENV_NAME, 'site' => self::SITE_ID, 'message' => "Try to commit",];
        $handler = new EnvCommitHandler($this->api);
        $result = $handler->handle($event);
    }
}
