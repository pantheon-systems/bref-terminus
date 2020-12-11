<?php declare(strict_types=1);

namespace Pantheon\Autopilot\Terminus\Tests\Integration;

use Pantheon\Autopilot\Terminus\Handlers\DeployEnvHandler;

/**
 * Class DeployEnvHandlerTest
 * @package Pantheon\Autopilot\Terminus\Tests\Integration
 */
class DeployEnvHandlerTest extends AbstractHandlerTestCase
{
    /**
     * Exercises the environment-deployment handler.
     */
    public function testDeployEnv(): void
    {
        $event = [
            'env' => self::DEV_ENV_NAME,
            'site' => self::SITE_ID,
            'annotation' => 'Deployment from Autopilot'
        ];
        $handler = new DeployEnvHandler($this->api);
        $result = $handler->handle($event);

        $this->assertIsValidUuid($result);
    }
}
