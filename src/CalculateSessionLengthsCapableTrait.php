<?php

namespace RebelCode\Sessions;

/**
 * Common functionality for calculating session lengths from min-max-step data.
 *
 * @since [*next-version*]
 */
trait CalculateSessionLengthsCapableTrait
{
    /**
     * Generates an array of session lengths, given the minimum and maximum lengths together with a step length.
     *
     * @since [*next-version*]
     *
     * @param int $min  The minimum session length, in seconds.
     * @param int $max  The maximum session length, in seconds.
     * @param int $step The incremental number of seconds.
     *
     * @return int[] A list of session lengths.
     */
    protected function _calculateSessionLengths($min, $max, $step)
    {
        $lengths = [];
        $range   = $max - $min;
        for ($i = 0; $i <= $range; $i += $step) {
            $lengths[] = min($min + $i, $max);
        }

        return $lengths;
    }
}
