<?php declare(strict_types=1);

namespace Pantheon\Autopilot\Terminus\Tests\Integration;

use Pantheon\Autopilot\Terminus\Handlers\ConnectionInfoHandler;

/**
 * Class ConnectionInfoHandlerTest
 * @package Pantheon\Autopilot\Terminus\Tests\Integration
 */
class ConnectionInfoHandlerTest extends AbstractHandlerTestCase
{
    /**
     * Exercises the connection information-retrieving handler.
     */
    public function testConnectionInfo(): void
    {
        $event = ['env' => self::DEV_ENV_NAME, 'site' => self::SITE_ID,];
        $handler = new ConnectionInfoHandler($this->api);
        $result = $handler->handle($event);
        $drush_server_regexp = "#appserver\.dev\.(" . self::UUID_REGEXP . ")\.drush\.in#";

        $this->assertRegExp($drush_server_regexp, $result['sftp_host']);
    }
}
