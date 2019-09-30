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

namespace ContaoBlackForest\Member\Import\Subscriber;

use ContaoBlackForest\Member\Import\Event\PostPrepareDataEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * The post prepare data event subscriber.
 */
class PostPrepareData implements EventSubscriberInterface
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
            PostPrepareDataEvent::NAME => array(
                array('setPropertyValuesFromSetting', 10)
            )
        );
    }

    /**
     * Set property values from setting.
     *
     * @param PostPrepareDataEvent $event The event.
     *
     * @return void
     */
    public function setPropertyValuesFromSetting(PostPrepareDataEvent $event)
    {
        $preparedData = $event->getPreparedData();
        $settings     = $event->getSettings();

        foreach ($preparedData as $index => $value) {
            $preparedData[$index] = $this->setPropertyValues($settings, $value);
        }

        $event->setPreparedData($preparedData);
    }

    /**
     * Set property values.
     *
     * @param $settings array The settings.
     *
     * @param $value    array The value
     *
     * @return mixed
     */
    protected function setPropertyValues($settings, $value)
    {
        foreach ($settings as $property => $propertyValue) {
            if (in_array($property, array('id', 'tstamp', 'title', 'importSource', 'translate_properties'), null)
                || empty($propertyValue)
            ) {
                continue;
            }

            $value[$property] = $propertyValue;
        }

        return $value;
    }
}
