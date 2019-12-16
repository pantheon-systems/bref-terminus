<?php declare(strict_types=1);

// Load our dependencies and our env-loader file.
require __DIR__.'/vendor/autoload.php';
require __DIR__.'/env-autoload.php';

use Pantheon\Terminus\Terminus;
use Pantheon\Terminus\Config\DefaultsConfig;
use Pantheon\Terminus\Config\YamlConfig;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Input\StringInput;
use Pantheon\Terminus\Session\Session;
use Pantheon\Terminus\Collections\Sites;
use Pantheon\Terminus\Request\Request;

// TODO: We could wrap up a Terminus init method that returns the container.
function initTerminus() {
    // Terminus does not work right without constants.yml. We do not bother
    // to load other configuration sources, though.
    $config = new DefaultsConfig();
    $config->extend(new YamlConfig($config->get('root') . '/config/constants.yml'));
    $config->set('TERMINUS_USER_HOME', '/tmp/otto-terminus');

    // We need $input and $output in order to initialize the Robo container.
    $input = new StringInput('');
    $output = new BufferedOutput();

    // Init the Terminus object. We only need this for calling Terminus
    // commands, but we won't do that; we'll call API functions directly.
    $terminus = new Terminus($config, $input, $output);

    // We need a reference to the container to call the API.
    return \Robo\Robo::getContainer();
}

// TODO: Perhaps we could make a login method in the Session class
function login($session, $machine_token) {
    $tokens = $session->getTokens();
    try {
        $token = $tokens->get($machine_token);
    } catch (\Exception $e) {
        $tokens->create($machine_token);
        return $tokens->get($machine_token);
    }

    if (isset($token)) {
        $token->logIn();
        return $token;
    }

    return null;
}

// We need a reference to the container to create Terminus API objects.
$container = initTerminus();

lambda(function ($event) use ($container) {

    // We need the session and request objects from the container
    $session = $container->get('session');
    $request = $container->get('request');

    // Look up the machine token. Throw an exception if there is no token
    // defined, or if the token value equals the default from the dist file.
    $machine_token = getenv('TERMINUS_TOKEN');
    if (!$machine_token || ($machine_token == 'MACHINE_TOKEN')) {
      throw new \RuntimeException('No machine token defined.');
    }

    // We expect the site to be provided in the function data, but if there
    // is none, we will look up a default from the environment values.
    $site_id = $event['site'] ?? getenv('SITE_ID');
    if (!$site_id) {
      throw new \RuntimeException('No site specified.');
    }

    $token = login($session, $machine_token);
    if (isset($token)) {
        $sites = new Sites();
        $sites->setSession($session);
        $sites->setContainer($container);
        $sites->setRequest($request);

        $site = $sites->get($site_id);

        return $site->serialize();
    }

    throw new \RuntimeException('Could not log in.');
});
