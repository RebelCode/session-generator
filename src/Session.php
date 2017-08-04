<?php

namespace RebelCode\Sessions;

/**
 * A simple implementation of a session.
 *
 * @since [*next-version*]
 */
class Session implements SessionInterface
{
    /**
     * The start timestamp for this session.
     *
     * @since [*next-version*]
     *
     * @var int
     */
    protected $start;

    /**
     * The end timestamp for this session.
     *
     * @since [*next-version*]
     *
     * @var int
     */
    protected $end;

    /**
     * Constructor.
     *
     * @since [*next-version*]
     *
     * @param int $start The start timestamp for this session.
     * @param int $end   The end timestamp for this session.
     */
    public function __construct($start, $end)
    {
        $this->_setStart($start)
            ->_setEnd($end);
    }

    /**
     * Retrieves the start timestamp for this session.
     *
     * @since [*next-version*]
     *
     * @return int
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Sets the start timestamp for this session.
     *
     * @since [*next-version*]
     *
     * @param int $start The start timestamp for this session.
     *
     * @return $this
     */
    protected function _setStart($start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * Retrieves the end timestamp for this session.
     *
     * @since [*next-version*]
     *
     * @return int
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * Sets the end timestamp for this session.
     *
     * @since [*next-version*]
     *
     * @param int $end The end timestamp for this session.
     *
     * @return $this
     */
    protected function _setEnd($end)
    {
        $this->end = $end;

        return $this;
    }
}
