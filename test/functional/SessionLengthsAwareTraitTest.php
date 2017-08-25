<?php

namespace RebelCode\Sessions\FuncTest;

use Xpmock\TestCase;

/**
 * Tests {@see \RebelCode\Sessions\SessionLengthsAwareTrait}.
 *
 * @since [*next-version*]
 */
class SessionLengthsAwareTraitTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'RebelCode\Sessions\SessionLengthsAwareTrait';

    /**
     * Creates a new instance of the test subject.
     *
     * @since [*next-version*]
     */
    public function createInstance()
    {
        // Create mock
        $mock = $this->getMockForTrait(static::TEST_SUBJECT_CLASSNAME);

        return $mock;
    }

    /**
     * Tests the session lengths getter and setter methods.
     *
     * @since [*next-version*]
     */
    public function testGetSetSessionLengths()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);
        $lengths = [rand(1, 250), rand(1, 500), rand(1, 750)];

        $reflect->_setSessionLengths($lengths);

        $this->assertSame($lengths, $reflect->_getSessionLengths(), 'The set and retrieved lengths do not match');
    }
}
