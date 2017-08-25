<?php


namespace RebelCode\Sessions;

use Traversable;

/**
 * Something that is aware of session lengths.
 *
 * @since [*next-version*]
 */
trait SessionLengthsAwareTrait
{
    /**
     * The session lengths, in seconds.
     *
     * @since [*next-version*]
     *
     * @var int[]
     */
    protected $sessionLengths;

    /**
     * Retrieves the session lengths.
     *
     * @since [*next-version*]
     *
     * @return int[]|Traversable The session lengths, in seconds.
     */
    protected function _getSessionLengths()
    {
        return $this->sessionLengths;
    }

    /**
     * Sets the session lengths.
     *
     * @since [*next-version*]
     *
     * @param int[]|Traversable $sessionLengths The session lengths in seconds.
     *
     * @return $this
     */
    protected function _setSessionLengths($sessionLengths)
    {
        $this->sessionLengths = $sessionLengths;

        return $this;
    }
}
