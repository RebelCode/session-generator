<?php

namespace RebelCode\Sessions;

use Exception;
use Dhii\Validation\ValidatorInterface;
use InvalidArgumentException;

/**
 * Common functionality for objects that are aware of a validator.
 *
 * @since [*next-version*]
 */
trait ValidatorAwareTrait
{
    /**
     * The validator instance.
     *
     * @since [*next-version*]
     *
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * Retrieves the validator.
     *
     * @since [*next-version*]
     *
     * @return ValidatorInterface The validator instance.
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
     * @param ValidatorInterface|null $validator The validator instance to set.
     *
     * @return $this
     *
     * @throws InvalidArgumentException If the argument is not null and not a valid validator instance.
     */
    protected function _setValidator($validator)
    {
        if ($validator !== null && !($validator instanceof ValidatorInterface)) {
            throw $this->_createInvalidArgumentException(
                $this->__('Argument is not a valid validator instance.'),
                0,
                null,
                $validator
            );
        }

        $this->validator = $validator;

        return $this;
    }

    /**
     * Creates a new Dhii invalid argument exception.
     *
     * @since [*next-version*]
     *
     * @param string    $message  The error message.
     * @param int       $code     The error code.
     * @param Exception $previous The inner exception for chaining, if any.
     * @param mixed     $argument The invalid argument, if any.
     *
     * @return InvalidArgumentException The new exception.
     */
    abstract protected function _createInvalidArgumentException(
        $message = '',
        $code = 0,
        Exception $previous = null,
        $argument = null
    );

    /**
     * Translates a string, and replaces placeholders.
     *
     * @since [*next-version*]
     * @see   sprintf()
     *
     * @param string $string  The format string to translate.
     * @param array  $args    Placeholder values to replace in the string.
     * @param mixed  $context The context for translation.
     *
     * @return string The translated string.
     */
    abstract protected function __($string, $args = [], $context = null);
}
