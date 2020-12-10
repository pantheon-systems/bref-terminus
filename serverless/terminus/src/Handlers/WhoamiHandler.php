<?php declare(strict_types=1);

namespace Pantheon\Autopilot\Terminus\Handlers;

/**
 * Class WhoamiHandler
 * @package Pantheon\Autopilot\Terminus\Handlers
 */
class WhoamiHandler extends AbstractHandler
{
    /**
     * {@inheritdoc}
     *
     * @return array
     * @throws \Pantheon\Terminus\Exceptions\TerminusException
     */
    public function action(array $event): array
    {
        $session = $this->api->session();
        if (!$session->isActive()) {
            throw new \Exception('Could not authenticate.');
        }
        $user = $session->getUser();
        $user->fetch();
        return $user->serialize();
    }
}
