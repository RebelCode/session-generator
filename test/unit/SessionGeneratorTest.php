<?php

namespace RebelCode\Sessions\UnitTest;

use DateTime;
use RebelCode\Sessions\SessionGenerator;
use Xpmock\TestCase;

class SessionGeneratorTest extends TestCase
{
    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public static function setUpBeforeClass()
    {
        date_default_timezone_set('UTC');
    }

    /**
     * Creates a session factory for testing purposes.
     *
     * @since [*next-version*]
     *
     * @return callable
     */
    public function createSessionFactory()
    {
        return function($start, $end) {
            return [$start, $end];
        };
    }

    /**
     * Tests whether a valid instance of the test subject can be created.
     *
     * @since [*next-version*]
     */
    public function testCanBeCreated()
    {
        $subject = new SessionGenerator($this->createSessionFactory(), 0);

        $this->assertInstanceOf(
            'RebelCode\\Sessions\\SessionGeneratorInterface', $subject,
            'Test subject does not implement expected interface.'
        );
    }

    /**
     * Tests the session generation functionality.
     *
     * @since [*next-version*]
     */
    public function testGenerate()
    {
        // 10 minute long sessions
        $subject = new SessionGenerator($this->createSessionFactory(), [600], 0, null);
        $start    = strtotime('01/08/2017 02:00');
        $end      = strtotime('01/08/2017 03:00');
        $sessions = $subject->generate($start, $end);
        $t        = $start;

        $expected = [
            [$t +     0, $t +  600],
            [$t +   600, $t + 1200],
            [$t +  1200, $t + 1800],
            [$t +  1800, $t + 2400],
            [$t +  2400, $t + 3000],
            [$t +  3000, $t + 3600],
        ];

        $this->assertEquals($expected, $sessions,'',0, 10, true);
    }
}
