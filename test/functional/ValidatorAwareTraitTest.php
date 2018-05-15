<?php

namespace RebelCode\Sessions\FuncTest;

use Dhii\Validation\ValidatorInterface;
use Xpmock\TestCase;

/**
 * Tests {@see \RebelCode\Sessions\ValidatorAwareTrait}.
 *
 * @since [*next-version*]
 */
class ValidatorAwareTraitTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'RebelCode\Sessions\ValidatorAwareTrait';

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
     * Creates a validator for testing purposes.
     *
     * @since [*next-version*]
     *
     * @return ValidatorInterface
     */
    public function createValidator()
    {
        $mock = $this->mock('Dhii\Validation\ValidatorInterface')
                     ->validate();

        return $mock->new();
    }

    /**
     * Tests the validator getter and setter methods to ensure correct value assignment and retrieval.
     *
     * @since [*next-version*]
     */
    public function testGetSetValidator()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);
        $validator = $this->createValidator();

        $reflect->_setValidator($validator);

        $this->assertSame(
            $validator,
            $reflect->_getValidator(),
            'Set and retrieved validator are not the same.'
        );
    }

    /**
     * Tests the validator getter and setter methods with a null value to ensure correct value assignment and retrieval.
     *
     * @since [*next-version*]
     */
    public function testGetSetValidatorNull()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $reflect->_setValidator(null);

        $this->assertNull(
            $reflect->_getValidator(),
            'Retrieved validator should be null.'
        );
    }

    /**
     * Tests the validator getter and setter methods with an invalid value to ensure that an exception is thrown.
     *
     * @since [*next-version*]
     */
    public function testGetSetValidatorFailure()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $this->setExpectedException('InvalidArgumentException');

        $reflect->_setValidator(new \stdClass());
    }
}
