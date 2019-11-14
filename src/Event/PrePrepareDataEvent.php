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

namespace ContaoBlackForest\MemberImportBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * The pre prepare data event.
 */
class PrePrepareDataEvent extends Event
{
    /**
     * The event name.
     */
    const NAME = 'cb.member_import.pre_prepare_data';

    /**
     * The import data.
     */
    protected $importData;

    /**
     * The data import settings.
     */
    protected $settings;

    /**
     * PrePrepareDataEvent constructor.
     *
     * @param array     $importData The import data.
     * @param \stdClass $settings   The import settings.
     */
    public function __construct($importData, $settings)
    {
        $this->importData      = $importData;
        $this->settings        = $settings;
    }

    /**
     * Return the import data.
     *
     * @return mixed
     */
    public function getImportData()
    {
        return $this->importData;
    }

    /**
     * Set the import data.
     *
     * @param mixed $importData
     *
     * @return void
     */
    public function setImportData($importData)
    {
        $this->importData = $importData;
    }

    /**
     * Return the import settings.
     *
     * @return mixed
     */
    public function getSettings()
    {
        return $this->settings;
    }
}
