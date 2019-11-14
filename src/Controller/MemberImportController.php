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

namespace ContaoBlackForest\MemberImportBundle\Controller;

use ContaoBlackForest\MemberImportBundle\View\Backend\BackendImportImport;
use ContaoBlackForest\MemberImportBundle\View\Backend\BackendImportLoad;
use ContaoBlackForest\MemberImportBundle\View\Backend\BackendImportMain;
use ContaoBlackForest\MemberImportBundle\View\Backend\BackendImportPrepare;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * The member import controller.
 *
 * @Route(defaults={"_scope" = "backend", "_token_check" = "true"})
 */
final class MemberImportController extends AbstractController
{
    /**
     * The main action, for the member import.
     *
     * @Route("/contao/cb/member/import", name="contao_cb_member_import")
     */
    public function mainAction()
    {
        $this->container->get('contao.framework')->initialize();

        return $this->container->get(BackendImportMain::class)->process();
    }

    /**
     * The load action, for the member import.
     *
     * @Route("/contao/cb/member/import/load", name="contao_cb_member_import_load")
     */
    public function loadAction()
    {
        $this->container->get('contao.framework')->initialize();

        return $this->container->get(BackendImportLoad::class)->process();
    }

    /**
     * The prepare action, for the member import.
     *
     * @Route("/contao/cb/member/import/prepare", name="contao_cb_member_import_prepare")
     */
    public function prepareAction()
    {
        $this->container->get('contao.framework')->initialize();

        return $this->container->get(BackendImportPrepare::class)->process();
    }

    /**
     * The import action, for the member import.
     *
     * @Route("/contao/cb/member/import/import", name="contao_cb_member_import_import")
     */
    public function importAction()
    {
        $this->container->get('contao.framework')->initialize();

        return $this->container->get(BackendImportImport::class)->process();
    }
}
