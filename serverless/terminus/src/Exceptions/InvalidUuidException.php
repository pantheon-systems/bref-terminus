<?php declare(strict_types=1);

namespace Pantheon\Autopilot\Terminus\Exceptions;

/**
 * Class InvalidUuidException
 * @package Pantheon\Autopilot\Terminus\Exceptions
 */
class InvalidUuidException extends \Exception
{
    const UUID_REGEXP = '[0-9a-fA-F]{8}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{12}';

    /**
     * InvalidUuidException constructor.
     * @param string $name Name of the parameter that exception is being taken to
     * @param string $value Value given which does not meet expectations
     */
    public function __construct($name, $value)
    {
        parent::__construct("The $name parameter must be a valid UUID. Provided \"$value\".");
    }

    /**
     * @param string $uuid String being tested for whether it is a UUID
     * @return bool True if parameter string is a valid UUID
     */
    public static function isValidUuid($uuid): bool
    {
        preg_match(
            '#' . InvalidUuidException::UUID_REGEXP . '#',
            $uuid,
            $matches
        );
        return !empty($matches);
    }
}
