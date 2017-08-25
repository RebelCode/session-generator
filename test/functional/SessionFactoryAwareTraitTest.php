<?php

namespace RebelCode\Sessions\FuncTest;

use Xpmock\TestCase;

/**
 * Tests {@see \RebelCode\Sessions\SessionFactoryAwareTrait}.
 *
 * @since [*next-version*]
 */
class SessionFactoryAwareTraitTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'RebelCode\Sessions\SessionFactoryAwareTrait';

    /**
     * Creates a new instance of the test subject.
     *
     * @since [*next-version*]
     */
    public function createInstance()
    {
        // Create mock
        $mock = $this->getMockForTrait(
            static::TEST_SUBJECT_CLASSNAME,
            [],
            '',
            false,
            true,
            true,
            [
                '_createInvalidArgumentException',
                '__',
            ]
        );

        $mock->expects($this->any())
             ->method('_createInvalidArgumentException')
             ->willReturn(new \InvalidArgumentException());

        $mock->expects($this->any())
             ->method('__')
             ->willReturnArgument(0);

        return $mock;
    }

    /**
     * Creates a factory for testing purposes.
     *
     * @since [*next-version*]
     *
     * @return callable
     */
    public function createFactory()
    {
        $mock = function () {
        };

        return $mock;
    }

    /**
     * Tests the session factory getter and setter methods to ensure correct value assignment and retrieval.
     *
     * @since [*next-version*]
     */
    public function testGetSetSessionFactory()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);
        $factory = $this->createFactory();

        $reflect->_setSessionFactory($factory);

        $this->assertSame(
            $factory,
            $reflect->_getSessionFactory(),
            'Set and retrieved session factories are not the same.'
        );
    }

    /**
     * Tests the session factory getter and setter methods with a null value to ensure correct value assignment and
     * retrieval.
     *
     * @since [*next-version*]
     */
    public function testGetSetSessionFactoryNull()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $reflect->_setSessionFactory(null);

        $this->assertNull(
            $reflect->_getSessionFactory(),
            'Retrieved session factory should be null.'
        );
    }

    /**
     * Tests the session factory getter and setter methods with an invalid value to ensure that an exception is thrown.
     *
     * @since [*next-version*]
     */
    public function testGetSetSessionFactoryFailure()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $this->setExpectedException('InvalidArgumentException');

        $reflect->_setSessionFactory(new \stdClass());
    }
}
