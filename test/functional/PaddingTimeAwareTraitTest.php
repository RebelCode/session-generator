<?php

namespace RebelCode\Sessions\FuncTest;

use Xpmock\TestCase;

/**
 * Tests {@see \RebelCode\Sessions\PaddingTimeAwareTrait}.
 *
 * @since [*next-version*]
 */
class PaddingTimeAwareTraitTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'RebelCode\Sessions\PaddingTimeAwareTrait';

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
     * Tests the padding time getter and setter methods.
     *
     * @since [*next-version*]
     */
    public function testGetSetPaddingTime()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);
        $padding = rand(0, 3600);

        $reflect->_setPaddingTime($padding);

        $this->assertSame(
            $padding,
            $reflect->_getPaddingTime(),
            'The set and retrieved padding time do not match.'
        );
    }
}
