<?php

/**
 * Copyright © ContaoBlackForest
 *
 * @package   contao-member-import
 * @author    Sven Baumann <baumann.sv@gmail.com>
 * @author    Dominik Tomasi <dominik.tomasi@gmail.com>
 * @license   GNU/LGPL
 * @copyright Copyright 2014-2016 ContaoBlackForest
 */

namespace ContaoBlackForest\Member\Import\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * The pre prepare data event.
 */
class PostPrepareDataEvent extends Event
{
    /**
     * The event name.
     */
    const NAME = 'ContaoBlackForest\Member\Import\Event\PostPrepareDataEvent';

    /**
     * The event dispatcher.
     *
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * The prepared data.
     */
    protected $preparedData;

    /**
     * The import settings.
     */
    protected $settings;

    /**
     * PrePrepareDataEvent constructor.
     *
     * @param EventDispatcherInterface $eventDispatcher The event dispatcher.
     *
     * @param array                    $preparedData    The prepared data.
     */
    public function __construct(EventDispatcherInterface $eventDispatcher, $preparedData, $settings)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->preparedData    = $preparedData;
        $this->settings        = $settings;
    }

    /**
     * Get the event dispatcher.
     *
     * @return mixed
     */
    public function getEventDispatcher()
    {
        return $this->eventDispatcher;
    }

    /**
     * Return the prepared data.
     *
     * @return mixed
     */
    public function getPreparedData()
    {
        return $this->preparedData;
    }

    /**
     * Set the prepared data.
     *
     * @param mixed $preparedData
     *
     * @return void
     */
    public function setPreparedData($preparedData)
    {
        $this->preparedData = $preparedData;
    }

    /**
     * Return the import settings.
     *
     * @return array
     */
    public function getSettings()
    {
        return $this->settings;
    }
}
