<?php

namespace RebelCode\Sessions\FuncTest;

use Xpmock\TestCase;

/**
 * Tests {@see \RebelCode\Sessions\CalculateSessionLengthsCapableTrait}.
 *
 * @since [*next-version*]
 */
class CalculateSessionLengthsCapableTraitTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'RebelCode\Sessions\CalculateSessionLengthsCapableTrait';

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
     * Tests the session length calculation with typical arguments.
     *
     * @since [*next-version*]
     */
    public function testCalculateSessionLengths()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $lengths = $reflect->_calculateSessionLengths(100, 300, 50);
        $this->assertEquals(
            [100, 150, 200, 250, 300],
            $lengths,
            'Calculated lengths do not match expected results.',
            0,
            10,
            true
        );
    }

    /**
     * Tests the session length calculation with a step value that does not align with the max value.
     *
     * @since [*next-version*]
     */
    public function testCalculateSessionLengthsNoMax()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $lengths = $reflect->_calculateSessionLengths(100, 500, 70);
        $this->assertEquals(
            [100, 170, 240, 310, 380, 450],
            $lengths,
            'Calculated lengths do not match expected results.',
            0,
            10,
            true
        );
    }

    /**
     * Tests the session length calculation with a min value larger than the max value.
     *
     * @since [*next-version*]
     */
    public function testCalculateSessionLengthsMinLargerThanMax()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $lengths = $reflect->_calculateSessionLengths(500, 200, 100);
        $this->assertEquals(
            [],
            $lengths,
            'Calculated lengths do not match expected results. Should be empty.',
            0,
            10,
            true
        );
    }

    /**
     * Tests the session length calculation with a step value that is larger than the range between the min and max
     * values.
     *
     * @since [*next-version*]
     */
    public function testCalculateSessionLengthsStepLargerThanRange()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $lengths = $reflect->_calculateSessionLengths(100, 400, 900);
        $this->assertEquals(
            [100],
            $lengths,
            'Calculated lengths do not match expected results. Should only contain min value.',
            0,
            10,
            true
        );
    }
}
