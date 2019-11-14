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

use Contao\FilesModel;
use ContaoBlackForest\MemberImportBundle\View\Backend\BackendTrait;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * This class process the import load.
 */
final class BackendImportLoad
{
    use BackendTrait;

    /**
     * {@inheritDoc}
     */
    public function process(): Response
    {
        $session = (array) $this->session->get($this->sessionKey);
        if (!isset($session['settings']) || !\count($session['settings'])) {
            $error = $this->twig->render(
                '@BlackForestMemberImport/Backend/be_member_import_error.html.twig',
                [
                    'message' => 'MSC.member_import_error_settings'
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
        if (!isset($session['data'])) {
            $session['data'] = [];
        }

        $setting = $session['settings'][\count($session['data'])];

        $session['data'][] = $this->prepareImportData($setting);
        $this->session->set($this->sessionKey, $session);

        $progress = (100 / \count($session['settings'])) * \count($session['data']);

        return new JsonResponse(['progress' => \round($progress)]);
    }

    /**
     * Prepare the import data.
     *
     * @param \stdClass $setting The setting.
     *
     * @return array
     */
    private function prepareImportData(\stdClass $setting): array
    {
        $filePath = $this->projectDir . DIRECTORY_SEPARATOR . $setting->importSource;
        if (!\file_exists($filePath)) {
            return [];
        }

        $content   = (new File($filePath))->openFile();
        $header    = $content->fgetcsv(';');
        $endOfFile = false;
        $data      = [];
        while (!$endOfFile) {
            $content->next();
            $lineData = $content->fgetcsv(';');

            $endOfFile = !(bool) $lineData;
            if ($endOfFile || (\count($header) !== \count($lineData))) {
                continue;
            }

            $data[] = \array_combine($header, $lineData);
        }

        return $data;
    }
}
