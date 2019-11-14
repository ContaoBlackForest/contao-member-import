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

declare(strict_types=1);

namespace ContaoBlackForest\MemberImportBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * The pre prepare data event.
 */
final class PrePrepareDataEvent extends Event
{
    /**
     * The event name.
     */
    const NAME = 'cb.member_import.pre_prepare_data';

    /**
     * The import data.
     *
     * @var array
     */
    protected $importData;

    /**
     * The data import settings.
     *
     *  @var \stdClass
     */
    protected $setting;

    /**
     * The constructor.
     *
     * @param array     $importData The import data.
     * @param \stdClass $setting    The import setting.
     */
    public function __construct(array $importData, \stdClass$setting)
    {
        $this->importData = $importData;
        $this->setting    = $setting;
    }

    /**
     * Return the import data.
     *
     * @return array
     */
    public function getImportData(): array
    {
        return $this->importData;
    }

    /**
     * Set the import data.
     *
     * @param array $importData The import data.
     *
     * @return void
     */
    public function setImportData(array $importData): void
    {
        $this->importData = $importData;
    }

    /**
     * Return the import settings.
     *
     * @return \stdClass
     */
    public function getSetting(): \stdClass
    {
        return $this->setting;
    }
}
