<?php

namespace RebelCode\Sessions;

/**
 * Common functionality for something that is aware of some padding time between sessions.
 *
 * @since [*next-version*]
 */
trait PaddingTimeAwareTrait
{
    /**
     * The padding time.
     *
     * @since [*next-version*]
     *
     * @var int
     */
    protected $paddingTime;

    /**
     * Retrieves the padding time.
     *
     * @since [*next-version*]
     *
     * @return int The padding time, in seconds.
     */
    protected function _getPaddingTime()
    {
        return $this->paddingTime;
    }

    /**
     * Sets the padding time.
     *
     * @since [*next-version*]
     *
     * @param int $paddingTime The padding time, in seconds.
     *
     * @return $this
     */
    protected function _setPaddingTime($paddingTime)
    {
        $this->paddingTime = $paddingTime;

        return $this;
    }
}
