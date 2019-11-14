<?php

/**
 * This file is part of contaoblackforest/contao-member-import.
 *
 * (c) 2016-2019 The Contao Blackforest team.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    contaoblackforest/contao-member-import
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @copyright  20116-2019 The Contao Blackforest team.
 * @license    https://github.com/contaoblackforest/contao-member-import/blob/master/LICENSE LGPL-3.0-or-later
 * @filesource
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
     * @param array $preparedData The prepared data.
     * @param       $settings
     */
    public function __construct($preparedData, $settings)
    {
        $this->preparedData    = $preparedData;
        $this->settings        = $settings;
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
