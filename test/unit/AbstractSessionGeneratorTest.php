<?php

namespace RebelCode\Sessions\UnitTest;

use Dhii\Util\Invocation\InvocableInterface;
use Dhii\Validation\ValidatorInterface;
use RebelCode\Sessions\AbstractSessionGenerator;
use Traversable;
use Xpmock\MockWriter;
use Xpmock\TestCase;

/**
 * Tests {@see \RebelCode\Sessions\AbstractSessionGenerator}.
 *
 * @since [*next-version*]
 */
class AbstractSessionGeneratorTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'RebelCode\Sessions\AbstractSessionGenerator';

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
     * Creates a new instance of the test subject.
     *
     * @since [*next-version*]
     *
     * @return MockWriter
     */
    public function createMock()
    {
        $mock = $this->mock(static::TEST_SUBJECT_CLASSNAME);

        return $mock;
    }

    /**
     * Creates a new instance of the test subject.
     *
     * @since [*next-version*]
     *
     * @param array|Traversable       $lengths   The session lengths to generate, in seconds.
     * @param int                     $padding   The padding time between sessions, in seconds.
     * @param callable|null           $factory   The callable that can create sessions.
     * @param ValidatorInterface|null $validator The validator to validate generated sessions.
     * @param callable|null           $invalidCb The callable to invoke for generated invalid sessions.
     *
     * @return AbstractSessionGenerator
     */
    public function createInstance(
        $lengths = [],
        $padding = 0,
        $factory = null,
        $validator = null,
        $invalidCb = null
    ) {
        $mock = $this->createMock()
                     ->_getSessionLengths($lengths)
                     ->_getPaddingTime($padding)
                     ->_getSessionFactory($this->returnValue($factory))
                     ->_getSessionValidator($validator)
                     ->_getOnSessionInvalidCallback($this->returnValue($invalidCb))
                     ->new();

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
     * Creates an invocable instance for testing purposes.
     *
     * @since [*next-version*]
     *
     * @return InvocableInterface
     */
    public function createInvocable()
    {
        $mock = $this->mock('Dhii\Util\Invocation\InvocableInterface')
                      ->__invoke();

        return $mock->new();
    }

    public function createValidationFailedException()
    {
        $mock = $this->mock('Dhii\Validation\Exception\ValidationFailedException');

        return $mock->new();
    }

    /**
     * Tests whether a valid instance of the test subject can be created.
     *
     * @since [*next-version*]
     */
    public function testCanBeCreated()
    {
        $subject = $this->createInstance();

        $this->assertInstanceOf(
            static::TEST_SUBJECT_CLASSNAME,
            $subject,
            'A valid instance of the test subject could not be created.'
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
        $subject = $this->createInstance(
            [600],
            0,
            function ($start, $end) {
                return [$start, $end];
            },
            null,
            null
        );
        $reflect = $this->reflect($subject);

        $start = strtotime('01/08/2017 02:00');
        $end = strtotime('01/08/2017 03:00');
        $sessions = $reflect->_generate($start, $end);
        $t = $start;

        $expected = [
            [$t + 0, $t + 600],
            [$t + 600, $t + 1200],
            [$t + 1200, $t + 1800],
            [$t + 1800, $t + 2400],
            [$t + 2400, $t + 3000],
            [$t + 3000, $t + 3600],
        ];

        $this->assertEquals($expected, $sessions, '', 0, 10, true);
    }

    /**
     * Tests the session generation functionality with padding time.
     *
     * @since [*next-version*]
     */
    public function testGenerateWithPadding()
    {
        // 8 minute long sessions with 2 minute padding
        $subject = $this->createInstance(
            [480],
            120,
            function ($start, $end) {
                return [$start, $end];
            },
            null,
            null
        );
        $reflect = $this->reflect($subject);

        $start = strtotime('01/08/2017 02:00');
        $end = strtotime('01/08/2017 03:00');
        $sessions = $reflect->_generate($start, $end);
        $t = $start;

        $expected = [
            [$t + 0, $t + 480],
            [$t + 600, $t + 1080],
            [$t + 1200, $t + 1680],
            [$t + 1800, $t + 2280],
            [$t + 2400, $t + 2880],
            [$t + 3000, $t + 3480],
        ];

        $this->assertEquals($expected, $sessions, '', 0, 10, true);
    }

    /**
     * Tests the session generation functionality to ensure that validation is being performed for each generated
     * session.
     *
     * @since [*next-version*]
     */
    public function testGenerateValidation()
    {
        $factory = function () {
            return;
        };
        $validator = $this->createValidator();

        $subject = $this->createMock()
                        ->_getSessionLengths([3600])// 1 hour
                        ->_getPaddingTime(0)
                        ->_getSessionFactory($this->returnValue($factory))
                        ->_getSessionValidator($validator)
                        ->_getOnSessionInvalidCallback(null)
                        ->new();

        $reflect = $this->reflect($subject);

        // Use a range of 3 hours to simulate generating 3 sessions
        $start = strtotime('01/08/2017 02:00');
        $end = strtotime('01/08/2017 05:00');

        // Expect validation to be performed twice
        $validator->mock()
                  ->validate([$this->anything()], $this->exactly(3));

        $reflect->_generate($start, $end);
    }

    /**
     * Tests the session generation functionality with failed validation to ensure that the validation failure
     * callback is invoked.
     *
     * @since [*next-version*]
     */
    public function testGenerateValidationFailedCallback()
    {
        $factory = function () {
            return;
        };
        $validator = $this->createValidator();
        $invalidCb = $this->createInvocable();

        $subject = $this->createMock()
                        ->_getSessionLengths([3600])// 1 hour
                        ->_getPaddingTime(0)
                        ->_getSessionFactory($this->returnValue($factory))
                        ->_getSessionValidator($validator)
                        ->_getOnSessionInvalidCallback($this->returnValue($invalidCb))
                        ->new();

        $reflect = $this->reflect($subject);

        // Use a range of 3 hours to simulate generating 3 sessions
        $start = strtotime('01/08/2017 02:00');
        $end = strtotime('01/08/2017 05:00');

        // Mock validator to throw exceptions
        $validator->mock()
                  ->validate($this->createValidationFailedException());

        // Expect the invalid callback to be invoked twice
        $invalidCb->mock()
                  ->__invoke([$this->anything()], $this->exactly(3));

        $reflect->_generate($start, $end);
    }
}
