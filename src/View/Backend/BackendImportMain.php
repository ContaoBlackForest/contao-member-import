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
use Symfony\Component\HttpFoundation\Response;

/**
 * This class process the import main.
 */
final class BackendImportMain
{
    use BackendTrait;

    /**
     * The files model.
     *
     * @var FilesModel|null
     */
    private $filesModel;

    /**
     * {@inheritDoc}
     */
    public function process(): Response
    {
        $this->setupBackendMain();
        $this->setupBackendTemplate('be_main');

        $this->backendTemplate->title    = $this->translator->trans('MOD.member.0', [], 'contao_modules') . ' import';
        $this->backendTemplate->headline = $this->backendTemplate->title;

        $settings = $this->collectImportSetting();

        $this->backendTemplate->main = $this->twig->render(
            '@BlackForestMemberImport/Backend/be_member_import.html.twig',
            [
                'settings' => \array_column($settings, 'title')
            ]
        );


        $this->addStyleSheet('backend/css/be_member_import.css');
        $this->addJavascript('backend/js/be_member_import.js');

        return $this->doProcess();
    }

    /**
     * Collect the import settings.
     *
     * @return array
     */
    private function collectImportSetting(): array
    {
        $platform = $this->connection->getDatabasePlatform();
        $builder  = $this->connection->createQueryBuilder();
        $builder
            ->select('*')
            ->from($platform->quoteIdentifier('tl_member_import'))
            ->where($builder->expr()->neq($platform->quoteIdentifier('disable_import'), ':disable_import'))
            ->orderBy($platform->quoteIdentifier('title'))
            ->setParameter(':disable_import', 1);

        $statement = $builder->execute();
        if (!$statement->rowCount()) {
            return [];
        }

        $result   = $statement->fetchAll(\PDO::FETCH_OBJ);
        $settings = [];
        foreach ($result as $item) {
            if (!($source = $this->sourceExists($item->importSource))) {
                continue;
            }

            $item->importSource         = $source;
            $item->translate_properties =
                \unserialize((string) $item->translate_properties, ['allowed_classes' => false]) ?: [];
            $item->groups               =
                \unserialize((string) $item->groups, ['allowed_classes' => false]) ?: [];

            $settings[] = $item;
        }

        $this->session->remove($this->sessionKey);
        $this->session->set($this->sessionKey, ['settings' => $settings]);

        return $settings;
    }

    /**
     * Determine the source exists.
     *
     * @param string $uuid The uuid of the file in the database.
     *
     * @return string|null
     */
    private function sourceExists(string $uuid): ?string
    {
        if (!$this->filesModel) {
            $this->filesModel = $this->framework->getAdapter(FilesModel::class);
        }

        $fileModel = $this->filesModel->findByUuid($uuid);
        if (!$fileModel || !\file_exists($this->projectDir . DIRECTORY_SEPARATOR . $fileModel->path)) {
            return null;
        }

        return $fileModel->path;
    }
}
