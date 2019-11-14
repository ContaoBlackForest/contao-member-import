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
final class PostPrepareDataEvent extends Event
{
    /**
     * The event name.
     */
    const NAME = 'cb.member_import.post_prepare_data';

    /**
     * The prepared data.
     *
     * @var array
     */
    protected $preparedData;

    /**
     * The import setting.
     *
     * @var \stdClass
     */
    protected $setting;

    /**
     * The constructor.
     *
     * @param array     $preparedData The prepared data.
     * @param \stdClass $setting      The setting.
     */
    public function __construct(array $preparedData, \stdClass $setting)
    {
        $this->preparedData = $preparedData;
        $this->setting      = $setting;
    }

    /**
     * Return the prepared data.
     *
     * @return array
     */
    public function getPreparedData(): array
    {
        return $this->preparedData;
    }

    /**
     * Set the prepared data.
     *
     * @param array $preparedData The prepare data.
     *
     * @return void
     */
    public function setPreparedData(array $preparedData): void
    {
        $this->preparedData = $preparedData;
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
