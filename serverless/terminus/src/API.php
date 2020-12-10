<?php declare(strict_types=1);

namespace Pantheon\Autopilot\Terminus;

use Pantheon\Terminus\Collections\Sites;
use Pantheon\Terminus\Config\DefaultsConfig;
use Pantheon\Terminus\Config\YamlConfig;
use Pantheon\Terminus\Exceptions\TerminusNotFoundException;
use Pantheon\Terminus\Models\SavedToken;
use Pantheon\Terminus\Request\Request;
use Pantheon\Terminus\Session\Session;
use Pantheon\Terminus\Terminus;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\BufferedOutput;

/**
 * Class API
 * Terminus API to Yggdrasil.
 * @package Pantheon\Autopilot\Terminus
 */
class API
{
    /**
     * @var ContainerInterface
     */
    protected $container;
    /**
     * @var Env
     */
    protected $env;

    /**
     * API constructor.
     * @param Env|null $env
     */
    public function __construct(Env $env = null)
    {
        // If an environment was not provided, then create one.
        $this->env = $env ?? Env::create();

        // Terminus does not work right without constants.yml. We do not bother
        // to load other configuration sources, though.
        $config = new DefaultsConfig();
        $config->extend(new YamlConfig($config->get('root') . '/config/constants.yml'));

        // Reset the Terminus home directory so that any sessions created
        // when doing local development on the state machines do not overwrite
        // the user's Terminus CLI login session.
        //
        // Writes the cache/session file with secret

        $session_json = $this->env->get('TERMINUS_SESSION_JWT');

        if (! file_exists('/tmp/.terminus-autopilot/cache')) {
            mkdir('/tmp/.terminus-autopilot/cache', 0755, true);
        }
        file_put_contents('/tmp/.terminus-autopilot/cache/session', $session_json);

        $config->set('TERMINUS_USER_HOME', '/tmp/');
        $config->set('TERMINUS_CACHE_DIR', '/tmp/.terminus-autopilot/cache');
        
        $terminus_host_from_env = $this->env->get('TERMINUS_HOST', '');
        if ($terminus_host_from_env && $terminus_host_from_env != 'terminus.pantheon.io:443') {
            $config->set('TERMINUS_HOST', $terminus_host_from_env);
            $config->set('TERMINUS_VERIFY_HOST_CERT', 0);
        }

        // We need $input and $output in order to initialize the Robo container,
        // even though these are not used.
        $input = new StringInput('');
        $output = new BufferedOutput();

        // Init the Terminus app object. We must initialize Terminus this way, but
        // we do not need to keep a reference to the app.
        $terminus = new Terminus($config, $input, $output);

        // We need a reference to the container to call the API.
        $this->container = \Robo\Robo::getContainer();
    }

    /**
     * @return Env
     */
    public function env(): Env
    {
        return $this->env;
    }

    /**
     * @return Request
     */
    public function request(): Request
    {
        return $this->container->get('request');
    }

    /**
     * @return Session
     */
    public function session(): Session
    {
        return $this->container->get('session');
    }

    /**
     * @return Sites
     */
    public function sites(): Sites
    {
        return $this->container->get('sites');
    }
}
