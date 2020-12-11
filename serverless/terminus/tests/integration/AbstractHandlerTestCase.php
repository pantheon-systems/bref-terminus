<?php declare(strict_types=1);

namespace Pantheon\Autopilot\Terminus\Tests\Integration;

use Pantheon\Autopilot\Terminus\Exceptions\InvalidUuidException;
use Pantheon\Terminus\Collections\Environments;
use Pantheon\Terminus\Exceptions\TerminusException;
use Pantheon\Terminus\Exceptions\TerminusNotFoundException;
use PHPUnit\Framework\TestCase;

use Pantheon\Autopilot\Terminus\API;

/**
 * Class AbstractHandlerTestCase
 * @package Pantheon\Autopilot\Terminus\Tests\Integration
 */
abstract class AbstractHandlerTestCase extends TestCase
{
    const DEV_ENV_NAME = 'dev';
    const LOCK_PASSWORD = 'password';
    const LOCK_USERNAME = 'username';
    const UUID_REGEXP = InvalidUuidException::UUID_REGEXP;

    /* Drops-8 fixture  */
    const SITE_ID = '';  // Drops-8 fixture test site ID
    const SITE_NAME = ''; // Drops-8 fixture test site name
    const TARGET_ENV_NAME = 'integ-test';

    /* WordPress Fixture */
    const SITE_ID_WORDPRESS = ''; // Wordpress fixture site ID

    /* Empty Upstream Fixture */
    const SITE_ID_EMPTY_UPSTREAM = '';  // empty upstream site ID

    /**
     * @var API
     */
    protected $api;
    /**
     * @var array
     */
    private $environments = [];

    /**
     * {@inheritDoc}
     *
     * Instantiates the Autopilot Terminus API property.
     */
    public function setUp()
    {
        parent::setUp();
        $this->api = new API();
    }

    /**
     * @param string $haystack
     */
    protected function assertIsValidUuid($haystack): void
    {
        $this->assertRegExp('#^' . self::UUID_REGEXP . '$#', $haystack);
    }

    /**
     * If the environment exists on the indicated site, it is deleted.
     *
     * @param string $site_id
     * @param string $env_name
     * @throws TerminusException
     * @throws TerminusNotFoundException
     */
    protected function ensureEnvDoesNotExist($site_id, $env_name): void
    {
        if ($this->envExists($site_id, $env_name)) {
            $this->deleteEnv($site_id, $env_name);
        }
    }

    /**
     * If the environment does not exist on the indicated site, it is created.
     *
     * @param string $site_id
     * @param string $env_name
     * @param string $source_env_name
     * @throws TerminusException
     * @throws TerminusNotFoundException
     */
    protected function ensureEnvExists(
        string $site_id,
        string $env_name,
        string $source_env_name = self::DEV_ENV_NAME
    ): void {
        if (!$this->envExists($site_id, $env_name)) {
            $this->createEnv($site_id, $source_env_name, $env_name);
        }
    }

    /**
     * Creates an environment on the indicated site.
     *
     * @param string $site_id
     * @param string $source_env_name
     * @param string $target_env_name
     * @throws TerminusException
     * @throws TerminusNotFoundException
     */
    private function createEnv($site_id, $source_env_name, $target_env_name): void
    {
        $environments = $this->getEnvironments($site_id);
        $source_env = $environments->get($source_env_name);
        $environments->create($target_env_name, $source_env);
    }

    /**
     * Deletes an environment on the indicated site.
     *
     * @param string $site_id
     * @param string $env_name
     * @throws TerminusException
     * @throws TerminusNotFoundException
     */
    private function deleteEnv($site_id, $env_name): void
    {
        $this->getEnvironments($site_id)->get($env_name)->delete();
    }

    /**
     * @param string $site_id
     * @param string $env_name
     * @return bool True if $site_id.$env_name exists.
     * @throws TerminusException
     */
    private function envExists($site_id, $env_name): bool
    {
        return $this->getEnvironments($site_id)->has($env_name);
    }

    /**
     * Retrieves a Site's Environments object and saves it for retrieval during the same test.
     *
     * @param string $site_id A name or UUID identifying a site
     * @return Environments
     * @throws TerminusException
     */
    protected function getEnvironments($site_id): Environments
    {
        if (!isset($this->environments[$site_id])) {
            $this->environments[$site_id] = $this->api->sites()->get($site_id)->getEnvironments();
        }
        return $this->environments[$site_id];
    }
}
