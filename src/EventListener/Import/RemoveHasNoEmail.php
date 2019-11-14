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
 * The remove data, who has no email address.
 */
final class RemoveHasNoEmail
{
    /**
     * Unset data has no email.
     *
     * @param PrePrepareDataEvent $event The event.
     *
     * @return void
     */
    public function __invoke(PrePrepareDataEvent $event): void
    {
        $importData = $event->getImportData();

        foreach ($importData as $index => $value) {
            if (\array_key_exists('email', $value)
                && !empty($value['email'])
            ) {
                continue;
            }

            unset($importData[$index]);
        }

        $importData = \array_reverse(\array_reverse($importData));

        $event->setImportData($importData);
    }
}
