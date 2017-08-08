<?php

namespace RebelCode\Sessions;

use Dhii\Validation\Exception\ValidationFailedExceptionInterface;
use Dhii\Validation\ValidatorInterface;
use Traversable;

/**
 * Simple concrete implementation of a session generator.
 *
 * @since [*next-version*]
 */
class SessionGenerator implements SessionGeneratorInterface
{
    /**
     * The different possible session lengths, in seconds.
     *
     * @since [*next-version*]
     *
     * @var int[]
     */
    protected $sessionLengths;

    /**
     * The number of seconds between each session.
     *
     * @since [*next-version*]
     *
     * @var int
     */
    protected $paddingTime;

    /**
     * The session validator.
     *
     * @since [*next-version*]
     *
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * The session factory callable.
     *
     * @since [*next-version*]
     *
     * @var callable
     */
    protected $sessionFactory;

    /**
     * Constructor.
     *
     * @since [*next-version*]
     *
     * @param int[]|Traversable       $lengths        The session lengths.
     * @param int                     $padding        Optional padding time.
     * @param ValidatorInterface|null $validator      Optional validator instance.
     * @param callable|null           $sessionFactory Optional session factory callback.
     */
    public function __construct(
        $lengths,
        $padding = 0,
        ValidatorInterface $validator = null,
        callable $sessionFactory = null
    ) {
        $this->_setSessionLengths($lengths)
             ->_setPaddingTime($padding)
             ->_setValidator($validator)
             ->_setSessionFactory($sessionFactory);
    }

    /**
     * Retrieves the session lengths.
     *
     * @since [*next-version*]
     *
     * @return int[]|Traversable
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
     * @param int[]|Traversable $sessionLengths
     *
     * @return $this
     */
    protected function _setSessionLengths($sessionLengths)
    {
        $this->sessionLengths = $sessionLengths;

        return $this;
    }

    /**
     * Retrieves the padding time.
     *
     * @since [*next-version*]
     *
     * @return int
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
     * @param int $paddingTime
     *
     * @return $this
     */
    protected function _setPaddingTime($paddingTime)
    {
        $this->paddingTime = $paddingTime;

        return $this;
    }

    /**
     * Retrieves the validator.
     *
     * @since [*next-version*]
     *
     * @return ValidatorInterface
     */
    protected function _getValidator()
    {
        return $this->validator;
    }

    /**
     * Sets the validator.
     *
     * @since [*next-version*]
     *
     * @param ValidatorInterface $validator
     *
     * @return $this
     */
    protected function _setValidator(ValidatorInterface $validator = null)
    {
        $this->validator = $validator;

        return $this;
    }

    /**
     * Validates a session, if a validator is available.
     *
     * @since [*next-version*]
     *
     * @param mixed $session
     *
     * @return bool
     */
    protected function _validateSession($session)
    {
        $validator = $this->_getValidator();

        if (is_null($validator)) {
            return true;
        }

        try {
            $validator->validate($session);

            return true;
        } catch (ValidationFailedExceptionInterface $e) {
            return false;
        }
    }

    /**
     * Retrieves the session factory callback.
     *
     * @since [*next-version*]
     *
     * @return callable
     */
    protected function _getSessionFactory()
    {
        return $this->sessionFactory;
    }

    /**
     * Sets the session factory callback.
     *
     * @since [*next-version*]
     *
     * @param callable|null $sessionFactory The session factory callback.
     *
     * @return $this
     */
    protected function _setSessionFactory(callable $sessionFactory = null)
    {
        $this->sessionFactory = $sessionFactory;

        return $this;
    }

    /**
     * Creates a session instance.
     *
     * @since [*next-version*]
     *
     * @param int $start The start timestamp for the session.
     * @param int $end   The end timestamp for the session.
     *
     * @return SessionInterface A session instance.
     */
    protected function _createSession($start, $end)
    {
        $factory = $this->_getSessionFactory();

        if (is_null($factory)) {
            return new Session($start, $end);
        }

        return call_user_func_array($factory, [$start, $end]);
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function generate($start, $end)
    {
        $results = [];
        $this->_generate($start, $end, $results);

        return $results;
    }

    /**
     * Generates sessions.
     *
     * @since [*next-version*]
     *
     * @param int   $rangeStart The range's starting timestamp.
     * @param int   $rangeEnd   The range's ending timestamp.
     * @param array $results    The results array that will be populated with the session instances.
     * @param array $startTimes A temporary array for storing the starting times that have already been processed, to
     *                          avoid repeating specific passes.
     */
    protected function _generate($rangeStart, $rangeEnd, array &$results = [], array &$startTimes = [])
    {
        foreach ($this->sessionLengths as $_length) {
            $_sessionStart = $rangeStart;
            $_sessionEnd   = $_sessionStart + $_length;

            if ($_sessionEnd > $rangeEnd) {
                break;
            }

            $session = $this->_createSession($_sessionStart, $_sessionEnd);

            if ($this->_validateSession($session)) {
                $results[]               = $session;
                $startTimes[$rangeStart] = 1;
            }

            // Calculate the start time for recursion
            $_nextStart = $_sessionEnd + $this->paddingTime;

            if (isset($startTimes[$_nextStart])) {
                continue;
            }

            $this->_generate($_nextStart, $rangeEnd, $results, $startTimes);
        }
    }

    /**
     * Generates an array of session lengths, given the minimum and maximum lengths together with a step length.
     *
     * @since [*next-version*]
     *
     * @param int $minLength  The minimum session length, in seconds.
     * @param int $maxLength  The maximum session length, in seconds.
     * @param int $stepLength The incremental number of seconds.
     *
     * @return int[] A list of session lengths.
     */
    public static function calculateLengths($minLength, $maxLength, $stepLength)
    {
        $lengths  = [];
        $numSteps = ceil(($maxLength - $minLength) / $stepLength);

        for ($i = 0; $i <= $numSteps; ++$i) {
            $lengths[] = min($minLength + ($stepLength * $i), $maxLength);
        }

        return $lengths;
    }
}
