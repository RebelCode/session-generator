<?php

namespace RebelCode\Sessions\UnitTest;

use RebelCode\Sessions\Session;
use Xpmock\TestCase;

/**
 * Tests {@see RebelCode\Sessions\Session}.
 *
 * @since [*next-version*]
 */
class SessionTest extends TestCase
{
    /**
     * Tests whether a valid instance of the test subject can be created.
     *
     * @since [*next-version*]
     */
    public function testCanBeCreated()
    {
        $subject = new Session(0, 0);

        $this->assertInstanceOf(
            'RebelCode\\Sessions\\SessionInterface', $subject,
            'Test subject does not implement expected interface.'
        );
    }

    /**
     * Tests the start timestamp getter method.
     *
     * @since [*next-version*]
     */
    public function testGetStart()
    {
        $subject = new Session($start = 12345000, 0);

        $this->assertEquals($start, $subject->getStart(),
            'Retrieved start timestamp does not match value given to constructor.');
    }

    /**
     * Tests the end timestamp getter method.
     *
     * @since [*next-version*]
     */
    public function testGetEnd()
    {
        $subject = new Session(0, $end = 12345000);

        $this->assertEquals($end, $subject->getEnd(),
            'Retrieved end timestamp does not match value given to constructor.');
    }
}
