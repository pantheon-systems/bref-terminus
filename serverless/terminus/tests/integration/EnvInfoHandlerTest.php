<?php declare(strict_types=1);

namespace Pantheon\Autopilot\Terminus\Tests\Integration;

use Pantheon\Autopilot\Terminus\Handlers\EnvInfoHandler;

/**
 * Class EnvInfoHandlerTest
 * @package Pantheon\Autopilot\Terminus\Tests\Integration
 */
class EnvInfoHandlerTest extends AbstractHandlerTestCase
{
    /**
     * Exercises the env information-retrieving handler.
     */
    public function testEnvInfo(): void
    {
        $event = ['env' => self::DEV_ENV_NAME, 'site' => self::SITE_ID,];
        $handler = new EnvInfoHandler($this->api);
        $result = $handler->handle($event);
        $domain_name = self::DEV_ENV_NAME . "-" . self::SITE_NAME . ".pantheonsite.io";

        $this->assertEquals($domain_name, $result['domain']);
    }
}
