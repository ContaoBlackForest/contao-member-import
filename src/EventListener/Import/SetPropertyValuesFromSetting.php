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

namespace ContaoBlackForest\MemberImportBundle\EventListener\Import;

use ContaoBlackForest\MemberImportBundle\Event\PostPrepareDataEvent;

/**
 * The set property values from setting.
 */
final class SetPropertyValuesFromSetting
{
    /**
     * Set property values from setting.
     *
     * @param PostPrepareDataEvent $event The event.
     *
     * @return void
     */
    public function __invoke(PostPrepareDataEvent $event): void
    {
        $preparedData = $event->getPreparedData();
        $setting      = $event->getSetting();

        foreach ($preparedData as $index => $value) {
            $preparedData[$index] = $this->setPropertyValues($setting, $value);
        }

        $event->setPreparedData($preparedData);
    }

    /**
     * Set property values.
     *
     * @param \stdClass $setting The settings.
     * @param array     $value   The value.
     *
     * @return mixed
     */
    private function setPropertyValues(\stdClass $setting, array $value): array
    {
        foreach ($setting as $property => $propertyValue) {
            if (empty($propertyValue)
                || \in_array($property, ['id', 'tstamp', 'title', 'importSource', 'translate_properties'], true)
            ) {
                continue;
            }

            $value[$property] = $propertyValue;
        }

        return $value;
    }
}
