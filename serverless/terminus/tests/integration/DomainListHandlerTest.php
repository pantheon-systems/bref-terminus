<?php declare(strict_types=1);

namespace Pantheon\Autopilot\Terminus\Tests\Integration;

use Pantheon\Autopilot\Terminus\Handlers\DomainListHandler;

/**
 * Class DomainListHandlerTest
 *
 * @package Pantheon\Autopilot\Terminus\Tests\Integration
 */
class DomainListHandlerTest extends AbstractHandlerTestCase
{
    /**
     * Exercises the domains list handler.
     */
    public function testDomainList(): void
    {

        $event = ['env' => self::DEV_ENV_NAME, 'site' => self::SITE_ID,];
        $handler = new DomainListHandler($this->api);
        $result = $handler->handle($event);
        $domain_name = self::DEV_ENV_NAME . "-" . self::SITE_NAME . ".pantheonsite.io";

        $this->assertArrayHasKey($domain_name, $result);
    }
}
