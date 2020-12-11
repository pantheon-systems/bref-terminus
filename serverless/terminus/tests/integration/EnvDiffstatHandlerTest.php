<?php declare(strict_types=1);

namespace Pantheon\Autopilot\Terminus\Tests\Integration;

use Pantheon\Autopilot\Terminus\Handlers\EnvDiffstatHandler;
use Pantheon\Autopilot\Terminus\Exceptions\DiffstatIsEmptyException;

/**
 * Class EnvDiffstatHandlerTest
 * @package Pantheon\Autopilot\Terminus\Tests\Integration
 */
class EnvDiffstatHandlerTest extends AbstractHandlerTestCase
{
    const UNCOMMITTED_FILES_ENV = 'uncommitted';
    /**
     * Exercises the env-diffstat handler on an empty environment.
     */
    public function testEnvEmptyDiffstat(): void
    {
        $this->expectException(DiffstatIsEmptyException::class);
        $this->expectExceptionMessage('The env diffstat is empty.');

        $event = ['env' => self::DEV_ENV_NAME, 'site' => self::SITE_ID,];
        $handler = new EnvDiffstatHandler($this->api);
        $result = $handler->handle($event);
    }

    /**
     * Exercises the env-diffstat handler on an environment with uncommitted files.
     */
    public function testEnvDiffstatWithFiles(): void
    {
        $event = ['env' => self::UNCOMMITTED_FILES_ENV, 'site' => self::SITE_ID_WORDPRESS,];
        $handler = new EnvDiffstatHandler($this->api);
        $result = $handler->handle($event);
        $this->assertNotEmpty($result);
    }
}
