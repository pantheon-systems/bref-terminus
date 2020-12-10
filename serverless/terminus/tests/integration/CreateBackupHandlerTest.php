<?php declare(strict_types=1);

namespace Pantheon\Autopilot\Terminus\Tests\Integration;

use Pantheon\Autopilot\Terminus\Handlers\CreateBackupHandler;

/**
 * Class CreateBackupHandlerTest
 * @package Pantheon\Autopilot\Terminus\Tests\Integration
 */
class CreateBackupHandlerTest extends AbstractHandlerTestCase
{
    /**
     * Exercises the backup-creation handler.
     */
    public function testCreateBackup(): void
    {
        $event = ['env' => self::DEV_ENV_NAME, 'site' => self::SITE_ID,];
        $handler = new CreateBackupHandler($this->api);
        $result = $handler->handle($event);

        $this->assertIsValidUuid($result);
    }
}
