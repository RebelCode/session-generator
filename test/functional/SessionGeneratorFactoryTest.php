<?php

namespace RebelCode\Sessions\FuncTest;

use PHPUnit_Framework_MockObject_MockObject as MockObject;
use RebelCode\Sessions\SessionGeneratorFactory as TestSubject;
use Xpmock\TestCase;

/**
 * Tests {@see \RebelCode\Sessions\SessionGeneratorFactory}.
 *
 * @since [*next-version*]
 */
class SessionGeneratorFactoryTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'RebelCode\Sessions\SessionGeneratorFactory';

    /**
     * Creates a mock validator instance.
     *
     * @since [*next-version*]
     *
     * @return MockObject The created validator instance.
     */
    public function createSessionValidator()
    {
        return $this->getMock('Dhii\Validation\ValidatorInterface');
    }

    /**
     * Tests whether a valid instance of the test subject can be created.
     *
     * @since [*next-version*]
     */
    public function testCanBeCreated()
    {
        $subject = new TestSubject();

        $this->assertInstanceOf(
            'RebelCode\Sessions\SessionGeneratorFactoryInterface',
            $subject,
            'An instance of the test subject could not be created'
        );
    }

    public function testMake()
    {
        $subject = new TestSubject();

        $config = [
            TestSubject::K_CFG_SESSION_FACTORY => function() {},
            TestSubject::K_CFG_SESSION_LENGTHS  => [],
            TestSubject::K_CFG_PADDING_TIME => 0,
            TestSubject::K_CFG_SESSION_VALIDATOR => $this->createSessionValidator()
        ];

        $actual = $subject->make($config);

        $this->assertInstanceOf(
            'RebelCode\Sessions\SessionGenerator',
            $actual,
            'Created instance in invalid.'
        );

        $this->assertInstanceOf(
            'RebelCode\Sessions\SessionGeneratorInterface',
            $actual,
            'Created instance does not implement expected interface.'
        );
    }

    public function testMakeNoOptionalConfig()
    {
        $subject = new TestSubject();

        $config = [
            TestSubject::K_CFG_SESSION_FACTORY => function() {},
            TestSubject::K_CFG_SESSION_LENGTHS  => []
        ];

        $actual = $subject->make($config);

        $this->assertInstanceOf(
            'RebelCode\Sessions\SessionGenerator',
            $actual,
            'Created instance in invalid.'
        );

        $this->assertInstanceOf(
            'RebelCode\Sessions\SessionGeneratorInterface',
            $actual,
            'Created instance does not implement expected interface.'
        );
    }

    public function testMakeOnlyOptionalConfig()
    {
        $subject = new TestSubject();

        $this->setExpectedException('Dhii\Factory\Exception\CouldNotMakeExceptionInterface');

        $config = [
            TestSubject::K_CFG_PADDING_TIME => 0,
            TestSubject::K_CFG_SESSION_VALIDATOR => $this->createSessionValidator()
        ];

        $subject->make($config);
    }

    public function testMakeNoConfig()
    {
        $subject = new TestSubject();

        $this->setExpectedException('Dhii\Factory\Exception\CouldNotMakeExceptionInterface');

        $subject->make();
    }
}
