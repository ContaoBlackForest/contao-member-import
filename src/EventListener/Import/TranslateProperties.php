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

use ContaoBlackForest\MemberImportBundle\Event\PrePrepareDataEvent;

/**
 * The translate the properties.
 */
final class TranslateProperties
{
    /**
     * Translate the properties names.
     *
     * @param PrePrepareDataEvent $event The event.
     *
     * @return void
     */
    public function __invoke(PrePrepareDataEvent $event): void
    {
        $importData = $event->getImportData();
        $setting    = $event->getSetting();

        if (!$translate = $this->prepareTranslation($setting)) {
            return;
        }

        foreach ($importData as $key => $data) {
            $importData[$key] = $this->translateData($data, $translate);
        }

        $event->setImportData($importData);
    }

    /**
     * Prepare the translation.
     *
     * @param \stdClass $setting The setting.
     *
     * @return array|null The translation.
     */
    private function prepareTranslation(\stdClass $setting): ?array
    {
        if (0 === \count($setting->translate_properties)) {
            return null;
        }

        $translate = [];
        foreach ($setting->translate_properties as $translate_property) {
            $translate[$translate_property['translation']] = $translate_property['property'];
        }

        return $translate;
    }

    /**
     * Translate the data.
     *
     * @param $data      array The data to translate.
     * @param $translate array The translation.
     *
     * @return array
     */
    private function translateData(array $data, array $translate): array
    {
        foreach ($translate as $property => $translation) {
            if (!\array_key_exists($property, $data)) {
                continue;
            }

            $data[$translation] = $data[$property];
            unset($data[$property]);
        }

        return $data;
    }
}
