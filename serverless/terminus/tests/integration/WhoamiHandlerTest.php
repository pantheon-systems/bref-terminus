<?php

namespace Pantheon\Autopilot\Terminus\Tests\Integration;

use Pantheon\Autopilot\Terminus\Handlers\WhoamiHandler;

/**
 * Class SiteInfoHandlerTest
 * @package Pantheon\Autopilot\Terminus\Tests\Integration
 */
class WhoamiHandlerTest extends AbstractHandlerTestCase
{
    /**
     * Exercises the site information-retrieval handler.
     */
    public function testWhoami(): void
    {
        $event = ['site' => self::SITE_ID,];
        $handler = new WhoamiHandler($this->api);
        $result = $handler->handle($event);

        // Assert that 'whoami' returned the right set of keys. We do not
        // care specifically which user was used to log in to run the test,
        // though.
        $keys = array_keys($result);
        sort($keys);
        $this->assertEquals('email,firstname,id,lastname', implode(',', $keys));
    }
}
