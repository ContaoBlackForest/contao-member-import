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

namespace ContaoBlackForest\Member\Import\Subscriber;

use ContaoBlackForest\Member\Import\Event\PrePrepareDataEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * The pre prepare data event subscriber.
 */
class PrePrepareData implements EventSubscriberInterface
{

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2')))
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return array(
            PrePrepareDataEvent::NAME => array(
                array('translateProperties', 20),
                array('unsetNonEmail', 10)
            )
        );
    }

    /**
     * Translate the properties names.
     *
     * @param PrePrepareDataEvent $event The event.
     *
     * @return void
     */
    public function translateProperties(PrePrepareDataEvent $event)
    {
        $importData = $event->getImportData();
        $settings   = $event->getSettings();

        if (!$translate = $this->prepareTranslation($settings)) {
            return;
        }

        foreach ($importData as $index => $value) {
            $importData[$index] = $this->translateData($value, $translate);
        }

        $event->setImportData($importData);
    }

    /**
     * Prepare the translation.
     *
     * @param $settings array The settings.
     *
     * @return array|null The translation.
     */
    protected function prepareTranslation($settings)
    {
        if (count($settings['translate_properties']) === 0) {
            return null;
        }

        $translate = array();
        foreach ($settings['translate_properties'] as $translate_property) {
            $translate[$translate_property['translation']] = $translate_property['property'];
        }

        return $translate;
    }

    /**
     * Translate the data.
     *
     * @param $data      array The data to translate.
     *
     * @param $translate array The translation.
     *
     * @return array
     */
    protected function translateData($data, $translate)
    {
        foreach ($translate as $property => $translation) {
            if (!array_key_exists($property, $data)) {
                continue;
            }

            $data[$translation] = $data[$property];
            unset($data[$property]);
        }

        return $data;
    }

    /**
     * Unset data has non email.
     *
     * @param PrePrepareDataEvent $event The event.
     *
     * @return void
     */
    public function unsetNonEmail(PrePrepareDataEvent $event)
    {
        $importData = $event->getImportData();

        foreach ($importData as $index => $value) {
            if (array_key_exists('email', $value)
                && !empty($value['email'])
            ) {
                continue;
            }

            // todo message for remove it has no email address.
            unset($importData[$index]);
        }

        $importData = array_reverse(array_reverse($importData));

        $event->setImportData($importData);
    }
}
