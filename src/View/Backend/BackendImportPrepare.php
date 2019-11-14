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

namespace ContaoBlackForest\MemberImportBundle\View\Backend;

use ContaoBlackForest\MemberImportBundle\Event\PostPrepareDataEvent;
use ContaoBlackForest\MemberImportBundle\Event\PrePrepareDataEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * This class process the import prepare.
 */
final class BackendImportPrepare
{
    use BackendTrait;

    /**
     * {@inheritDoc}
     */
    public function process(): Response
    {
        $session = (array) $this->session->get($this->sessionKey);
        if (!isset($session['data']) || !\count($session['data'])) {
            $error = $this->twig->render(
                '@BlackForestMemberImport/Backend/be_member_import_error.html.twig',
                [
                    'message' => 'MSC.member_import_error_data'
                ]
            );

            return new JsonResponse(['error' => $error]);
        }

        return $this->doProcess($session);
    }

    /**
     * Do process.
     *
     * @param array $session The session.
     *
     * @return JsonResponse
     */
    private function doProcess(array $session): JsonResponse
    {
        if (!isset($session['prepared'])) {
            $session['prepared'] = [];
        }

        $setting = $session['settings'][\count($session['prepared'])];
        $data    = $session['data'][\count($session['prepared'])];

        $session['prepared'][] = $this->prepareDate($data, $setting);
        $this->session->set($this->sessionKey, $session);

        $progress = (100 / \count($session['data'])) * \count($session['prepared']);

        return new JsonResponse(['progress' => \round($progress)]);
    }

    /**
     * Prepare the data.
     *
     * @param array     $data    The data.
     * @param \stdClass $setting The setting.
     *
     * @return array
     */
    private function prepareDate(array $data, \stdClass $setting): array
    {
        $preEvent = new PrePrepareDataEvent($data, $setting);
        $this->dispatcher->dispatch(PrePrepareDataEvent::NAME, $preEvent);

        $postEvent = new PostPrepareDataEvent($preEvent->getImportData(), $preEvent->getSettings());
        $this->dispatcher->dispatch(PostPrepareDataEvent::NAME, $postEvent);

        return (array) $postEvent->getPreparedData();
    }
}
