<?php

namespace RebelCode\Sessions;

use Dhii\Data\Container\ContainerGetCapableTrait;
use Dhii\Data\Container\ContainerHasCapableTrait;
use Dhii\Data\Container\CreateContainerExceptionCapableTrait;
use Dhii\Data\Container\CreateNotFoundExceptionCapableTrait;
use Dhii\Data\Container\NormalizeKeyCapableTrait;
use Dhii\Exception\CreateInvalidArgumentExceptionCapableTrait;
use Dhii\Exception\CreateOutOfRangeExceptionCapableTrait;
use Dhii\Factory\AbstractBaseCallbackFactory;
use Dhii\I18n\StringTranslatingTrait;
use Dhii\Util\Normalization\NormalizeStringCapableTrait;

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
}
