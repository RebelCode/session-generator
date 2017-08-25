<?php

namespace RebelCode\Sessions;

use Dhii\Validation\ValidatorInterface;
use Exception;
use InvalidArgumentException;

/**
 * Something that is aware of a session factory callable.
 *
 * @since [*next-version*]
 */
trait SessionFactoryAwareTrait
{
    /**
     * The session factory callable.
     *
     * @since [*next-version*]
     *
     * @var callable|null
     */
    protected $sessionFactory;

    /**
     * Retrieves the session factory.
     *
     * @since [*next-version*]
     *
     * @return callable|null The session factory callable.
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
     * @param callable|null $sessionFactory The session factory callable.
     *
     * @return $this
     */
    protected function _setSessionFactory($sessionFactory)
    {
        if ($sessionFactory !== null && !is_callable($sessionFactory)) {
            throw $this->_createInvalidArgumentException(
                $this->__('Argument is not a valid callable.'),
                0,
                null,
                $sessionFactory
            );
        }

        $this->sessionFactory = $sessionFactory;

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
