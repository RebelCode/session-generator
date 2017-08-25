<?php

namespace RebelCode\Sessions;

use Dhii\Exception\CreateInvalidArgumentExceptionCapableTrait;
use Dhii\I18n\StringTranslatingTrait;
use Dhii\Validation\ValidatorInterface;
use Traversable;

class SessionGenerator extends AbstractSessionGenerator implements SessionGeneratorInterface
{
    /*
     * Provides session length awareness.
     *
     * @since [*next-version*]
     */
    use SessionLengthsAwareTrait;

    /*
     * Provides padding time awareness.
     *
     * @since [*next-version*]
     */
    use PaddingTimeAwareTrait;

    /*
     * Provides session factory awareness.
     *
     * @since [*next-version*]
     */
    use SessionFactoryAwareTrait;

    /*
     * Provides validator awareness.
     */
    use ValidatorAwareTrait {
        ValidatorAwareTrait::_getValidator as _getSessionValidator;
        ValidatorAwareTrait::_setValidator as _setSessionValidator;
    }

    /*
     * Provides capability to create invalid argument exceptions.
     *
     * @since [*next-version*]
     */
    use CreateInvalidArgumentExceptionCapableTrait;

    /**
     * Provides the ability to translate and format strings.
     *
     * @since [*next-version*]
     */
    use StringTranslatingTrait;

    /**
     * Constructor.
     *
     * @since [*next-version*]
     *
     * @param callable                $factory   The session factory callable.
     * @param int[]|Traversable       $lengths   The session lengths, in seconds.
     * @param int                     $padding   Optional padding time between sessions, in seconds.
     * @param ValidatorInterface|null $validator Optional session validator.
     */
    public function __construct(callable $factory, $lengths, $padding = 0, $validator = null)
    {
        $this->_setSessionFactory($factory)
             ->_setSessionLengths($lengths)
             ->_setPaddingTime($padding)
             ->_setValidator($validator);
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function generate($start, $end)
    {
        return $this->_generate($start, $end);
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    protected function _getOnSessionInvalidCallback()
    {
        return;
    }
}
