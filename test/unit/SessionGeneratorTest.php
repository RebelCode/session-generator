<?php

namespace RebelCode\Sessions\UnitTest;

use DateTime;
use RebelCode\Sessions\Session;
use RebelCode\Sessions\SessionGenerator;
use Xpmock\TestCase;

class SessionGeneratorTest extends TestCase
{
    public static function setUpBeforeClass()
    {
        date_default_timezone_set('UTC');
    }

    /**
     * Tests whether a valid instance of the test subject can be created.
     *
     * @since [*next-version*]
     */
    public function testCanBeCreated()
    {
        $subject = new SessionGenerator(0);

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
        $subject = new SessionGenerator([600], 0, null, function($start, $end) {
            return [$start, $end];
        });
        $start    = new DateTime('01/08/2017 02:00');
        $end      = new DateTime('01/08/2017 03:00');
        $t        = $start->getTimestamp();
        $sessions = $subject->generate($start, $end);

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
