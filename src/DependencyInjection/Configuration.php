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

namespace ContaoBlackForest\MemberImportBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * The extension configuration.
 */
final class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder('cb_member_import');

        $node = $builder->root('cb_member_import');
        $node
            ->children()
                ->scalarNode('session_key')
                    ->cannotBeEmpty()
                    ->defaultValue('cb_member_import_session')
                ->end()
            ->end();

        return $builder;
    }
}
