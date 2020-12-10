<?php declare(strict_types=1);

namespace Pantheon\Autopilot\Terminus\Tests\Integration;

use Pantheon\Autopilot\Terminus\Handlers\SiteInfoHandler;

/**
 * Class SiteInfoHandlerTest
 * @package Pantheon\Autopilot\Terminus\Tests\Integration
 */
class SiteInfoHandlerTest extends AbstractHandlerTestCase
{
    /**
     * Exercises the site information-retrieval handler.
     */
    public function testSiteInfo(): void
    {
        $event = ['site' => self::SITE_ID,];
        $handler = new SiteInfoHandler($this->api);
        $result = $handler->handle($event);

        $this->assertEquals(self::SITE_NAME, $result['name']);
    }
}
