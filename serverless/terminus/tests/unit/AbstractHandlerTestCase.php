<?php declare(strict_types=1);

namespace Pantheon\Autopilot\Terminus\Tests\Unit;

use Pantheon\Autopilot\Terminus\API;
use Pantheon\Terminus\Collections\Sites;
use Pantheon\Terminus\Collections\Workflows;
use Pantheon\Terminus\Models\Site;
use Pantheon\Terminus\Models\Workflow;
use PHPUnit\Framework\TestCase;

/**
 * Class AbstractHandlerTestCase
 * @package Pantheon\Autopilot\Terminus\Tests\Unit
 */
abstract class AbstractHandlerTestCase extends TestCase
{
    const VALID_UUID = '11111111-1111-1111-1111-111111111111';
    /**
     * @var API
     */
    protected $api;
    /**
     * @var Site
     */
    protected $site;
    /**
     * @var Sites
     */
    protected $sites;
    /**
     * @var Workflow
     */
    protected $workflow;
    /**
     * @var Workflows
     */
    protected $workflows;

    /**
     * {@inheritDoc}
     *
     * Creates the API mock and assigns it to property
     */
    public function setUp()
    {
        parent::setUp();
        $this->setUpApiMock();
    }

    /**
     * Creates and assigns the API, Sites, Site, Workflows, and Workflow mock objects.
     */
    private function setUpApiMock()
    {
        $this->api = $this->createMock(API::class);
        $this->sites = $this->createMock(Sites::class);
        $this->site = $this->getMockBuilder(Site::class)
            ->disableOriginalConstructor()
            ->setMethodsExcept(['getConfig',])
            ->getMock();
        $this->workflows = $this->createMock(Workflows::class);
        $this->workflow = $this->getMockBuilder(Workflow::class)
            ->disableOriginalConstructor()
            ->setMethodsExcept(['getConfig',])
            ->getMock();

        $this->site->id = self::VALID_UUID;
        $this->workflow->id = self::VALID_UUID;

        $this->api->method('sites')
            ->willReturn($this->sites);
        $this->sites->method('get')
            ->willReturn($this->site);
        $this->site->method('getWorkflows')
            ->willReturn($this->workflows);
        $this->workflows->method('get')
            ->willReturn($this->workflow);
    }
}
