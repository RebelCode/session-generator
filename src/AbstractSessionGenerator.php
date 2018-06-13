<?php

namespace RebelCode\Sessions;

use ArrayAccess;
use Dhii\Validation\Exception\ValidationFailedExceptionInterface;
use Dhii\Validation\ValidatorInterface;
use Traversable;

/**
 * Abstract functionality for session generators that can generate sessions of variable lengths.
 *
 * @since [*next-version*]
 */
abstract class AbstractSessionGenerator
{
    /**
     * Generates sessions.
     *
     * @since [*next-version*]
     *
     * @param int $start The range's starting timestamp.
     * @param int $end   The range's ending timestamp.
     *
     * @return array A list of generated sessions.
     */
    protected function _generate($start, $end)
    {
        $lengths   = $this->_getSessionLengths();
        $padding   = $this->_getPaddingTime();
        $validator = $this->_getSessionValidator();
        $factory   = $this->_getSessionFactory();
        $invalidCb = $this->_getOnSessionInvalidCallback();

        return $this->_generateRecursive($start, $end, $lengths, $padding, $factory, $validator, $invalidCb);
    }

    /**
     * Generates sessions recursively.
     *
     * This method is highly optimized and requires references to the session factory, session validator, list of
     * results and list of processed start times to minimize function call overhead.
     *
     * @since [*next-version*]
     *
     * @param int                     $start      The range's starting timestamp.
     * @param int                     $end        The range's ending timestamp.
     * @param array|Traversable       $lengths    The length of the sessions to generate, in seconds.
     * @param int                     $padding    The padding time between sessions, in seconds.
     * @param callable                $factory    The session factory callable.
     * @param ValidatorInterface|null $validator  Optional session validator.
     * @param callable|null           $invalidCb  Optional callback to invoke for invalid sessions.
     *
     * @return array An array of generated sessions.
     */
    protected function _generateRecursive(
        $start,
        $end,
        $lengths,
        $padding,
        callable $factory,
        ValidatorInterface $validator = null,
        callable $invalidCb = null
    ) {
        $startTimes = [$start];
        $results    = [];
        $processed  = [];

        GENERATE_SESSIONS:

        $newStartTimes = [];
        foreach ($startTimes as $_start) {
            foreach ($lengths as $_length) {
                // Calculate end of session from range start time
                $_sessionEnd = $_start + $_length;

                // If exceeded range end, no more iteration is needed.
                if ($_sessionEnd > $end) {
                    break;
                }

                // Create session using the factory
                $session = call_user_func_array($factory, [$_start, $_sessionEnd]);

                try {
                    // Validate session and add to results
                    if ($validator !== null) {
                        $validator->validate($session);
                    }
                    $results[]          = $session;
                    $processed[$_start] = true;
                } catch (ValidationFailedExceptionInterface $exception) {
                    if ($invalidCb !== null) {
                        call_user_func_array($invalidCb, [$session, $exception]);
                    }
                }

                // Calculate the next start time for recursion, skipping if already generated
                $_next = $_sessionEnd + $padding;

                if (isset($processed[$_next])) {
                    continue;
                }

                $newStartTimes[] = $_next;
            }
        }

        $startTimes = $newStartTimes;

        if (count($newStartTimes) === 0) {
            return $results;
        }

        goto GENERATE_SESSIONS;
    }

    /**
     * Retrieves the session lengths.
     *
     * @since [*next-version*]
     *
     * @return int[]|Traversable The session lengths, in seconds.
     */
    abstract protected function _getSessionLengths();

    /**
     * Retrieves the padding time.
     *
     * @since [*next-version*]
     *
     * @return int The padding time, in seconds.
     */
    abstract protected function _getPaddingTime();

    /**
     * Retrieves the session factory.
     *
     * @since [*next-version*]
     *
     * @return callable|null The session factory callable.
     */
    abstract protected function _getSessionFactory();

    /**
     * Retrieves the validator.
     *
     * @since [*next-version*]
     *
     * @return ValidatorInterface The validator instance.
     */
    abstract protected function _getSessionValidator();

    /**
     * Retrieves the callback to invoke when a session is invalid.
     *
     * The callback will receive the following arguments:
     *   - the invalid session
     *   - the validation exception that was thrown
     *
     * @since [*next-version*]
     *
     * @return callable
     */
    abstract protected function _getOnSessionInvalidCallback();
}
