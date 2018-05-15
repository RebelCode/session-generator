<?php

namespace RebelCode\Sessions;

use Dhii\Data\Container\ContainerGetCapableTrait;
use Dhii\Data\Container\ContainerHasCapableTrait;
use Dhii\Data\Container\CreateContainerExceptionCapableTrait;
use Dhii\Data\Container\CreateNotFoundExceptionCapableTrait;
use Dhii\Data\Container\NormalizeKeyCapableTrait;
use Dhii\Exception\CreateInternalExceptionCapableTrait;
use Dhii\Exception\CreateInvalidArgumentExceptionCapableTrait;
use Dhii\Exception\CreateOutOfRangeExceptionCapableTrait;
use Dhii\Factory\AbstractBaseCallbackFactory;
use Dhii\I18n\StringTranslatingTrait;
use Dhii\Invocation\CreateReflectionForCallableCapableTrait;
use Dhii\Invocation\NormalizeCallableCapableTrait;
use Dhii\Invocation\NormalizeMethodCallableCapableTrait;
use Dhii\Invocation\ValidateParamsCapableTrait;
use Dhii\Util\Normalization\NormalizeStringCapableTrait;
use Dhii\Validation\CreateValidationFailedExceptionCapableTrait;
use ReflectionFunction;
use ReflectionMethod;

/**
 * A factory implementation that can create {@see SessionGenerator} instances.
 *
 * @since [*next-version*]
 */
class SessionGeneratorFactory extends AbstractBaseCallbackFactory implements SessionGeneratorFactoryInterface
{
    /*
     * Provides functionality for reading from any type of container.
     *
     * @since [*next-version*]
     */
    use ContainerGetCapableTrait;

    /*
     * Provides functionality for key-checking any type of container.
     *
     * @since [*next-version*]
     */
    use ContainerHasCapableTrait;

    /* @since [*next-version*] */
    use ValidateParamsCapableTrait;

    /* @since [*next-version*] */
    use CreateReflectionForCallableCapableTrait;

    /*
     * Provides key normalization functionality.
     *
     * @since [*next-version*]
     */
    use NormalizeKeyCapableTrait;

    /*
     * Provides string normalization functionality.
     *
     * @since [*next-version*]
     */
    use NormalizeStringCapableTrait;

    /* @since [*next-version*] */
    use NormalizeCallableCapableTrait;

    /* @since [*next-version*] */
    use NormalizeMethodCallableCapableTrait;

    /*
     * Provides functionality for creating container exceptions.
     *
     * @since [*next-version*]
     */
    use CreateContainerExceptionCapableTrait;

    /*
     * Provides functionality for creating not found exceptions.
     *
     * @since [*next-version*]
     */
    use CreateNotFoundExceptionCapableTrait;

    /*
     * Provides functionality for creating invalid-argument exceptions.
     *
     * @since [*next-version*]
     */
    use CreateInvalidArgumentExceptionCapableTrait;

    /*
     * Provides functionality for creating out-of-range exceptions.
     *
     * @since [*next-version*]
     */
    use CreateOutOfRangeExceptionCapableTrait;

    /* @since [*next-version*] */
    use CreateInternalExceptionCapableTrait;

    /* @since [*next-version*] */
    use CreateValidationFailedExceptionCapableTrait;

    /*
     * Provides string translation functionality.
     *
     * @since [*next-version*]
     */
    use StringTranslatingTrait;

    /**
     * The key in the config for the session factory.
     *
     * @since [*next-version*]
     */
    const K_CFG_SESSION_FACTORY = 'session_factory';

    /**
     * The key in the config for the session lengths.
     *
     * @since [*next-version*]
     */
    const K_CFG_SESSION_LENGTHS = 'session_lengths';

    /**
     * The key in the config for the padding time.
     *
     * @since [*next-version*]
     */
    const K_CFG_PADDING_TIME = 'padding_time';

    /**
     * The key in the config for the session validator.
     *
     * @since [*next-version*]
     */
    const K_CFG_SESSION_VALIDATOR = 'session_validator';

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function make($config = null)
    {
        return parent::make($config);
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    protected function _getFactoryCallback($config = null)
    {
        return function() use ($config) {
            $sessionFactory = $this->_containerGet($config, static::K_CFG_SESSION_FACTORY);
            $sessionLengths = $this->_containerGet($config, static::K_CFG_SESSION_LENGTHS);
            $paddingTime = $this->_containerHas($config, static::K_CFG_PADDING_TIME)
                ? $this->_containerGet($config, static::K_CFG_PADDING_TIME)
                : 0;
            $sessionValidator = $this->_containerHas($config, static::K_CFG_SESSION_VALIDATOR)
                ? $this->_containerGet($config, static::K_CFG_SESSION_VALIDATOR)
                : null;

            return new SessionGenerator($sessionFactory, $sessionLengths, $paddingTime, $sessionValidator);
        };
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    protected function _createReflectionFunction($functionName)
    {
        return new ReflectionFunction($functionName);
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    protected function _createReflectionMethod($className, $methodName)
    {
        return new ReflectionMethod($className, $methodName);
    }
}
