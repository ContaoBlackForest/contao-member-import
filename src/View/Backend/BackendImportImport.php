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

use Contao\Controller;
use Contao\DC_Table;
use Contao\Input;
use Contao\RequestToken;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * This class process the import.
 */
final class BackendImportImport
{
    use BackendTrait;

    /**
     * {@inheritDoc}
     */
    public function process(): Response
    {
        $session = (array) $this->session->get($this->sessionKey);
        if (!isset($session['prepared']) || !\count($session['prepared'])) {
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
        if (!isset($session['import'])) {
            $session['imported'] = [];
            $session['import']   = \array_merge(...$session['prepared']);
        }

        $data = \array_slice($session['import'], \count($session['imported']), 5);

        $session['imported'] = \array_merge($session['imported'], $this->doImport($data));
        $this->session->set($this->sessionKey, $session);

        $progress = (100 / \count($session['import'])) * \count($session['imported']);
        if (100 === (int) $progress) {
            $this->session->remove($this->sessionKey);
        }

        return new JsonResponse(['progress' => \round($progress)]);
    }

    /**
     * Prepare the data.
     *
     * @param array $data The data.
     *
     * @return array
     */
    private function doImport(array $data): array
    {
        foreach ($data as $importData) {
            $importData = $this->verifyData($importData);
            if (!\count($importData)) {
                continue;
            }

            $this->save($importData);
        }

        return $data;
    }

    /**
     * Verify the import data.
     *
     * @param $data array The data for verify.
     *
     * @return array
     */
    private function verifyData(array $data): array
    {
        $allowedProperties = $this->connection->getSchemaManager()->listTableColumns('tl_member');

        return \array_intersect_key($data, $allowedProperties);
    }

    /**
     * Save the data to the database.
     *
     * @param $data array The data to save.
     *
     * @return void
     */
    private function save(array $data): void
    {
        $platform = $this->connection->getDatabasePlatform();

        $builder = $this->connection->createQueryBuilder();
        $builder
            ->select('*')
            ->from($platform->quoteIdentifier('tl_member'))
            ->where($builder->expr()->eq($platform->quoteIdentifier('email'), ':email'))
            ->setParameter(':email', $data['email']);

        $statement = $builder->execute();
        if ($statement->rowCount()) {
            $result = $statement->fetch(\PDO::FETCH_ASSOC);
        } else {
            $result = [];
        }

        foreach ($data as $property => $value) {
            if (isset($result[$property]) && ('groups' === $property) && $result['groups']) {
                foreach (\unserialize($result['groups'], ['allowed_classes' => false]) as $group) {
                    if (\in_array($group, $value, true)) {
                        continue;
                    }

                    $value[]           = $group;
                    $data[$property][] = $group;
                }
            }

            if ('password' === $property) {
                if (isset($result[$property]) && $result[$property] && $value) {
                    unset($data[$property]);
                }

                continue;
            }

            if (\is_array($value)) {
                $value = \serialize($value);
            }

            if (isset($result[$property]) && ($value === $result[$property])) {
                unset($data[$property]);

                continue;
            }
        }

        if (!\count($data)) {
            return;
        }

        Controller::loadDataContainer('tl_member');

        $dcTable = new DC_Table('tl_member');

        $excludedProperties = \array_keys($this->connection->getSchemaManager()->listTableColumns('tl_member'));
        foreach ($excludedProperties as $excludedProperty) {
            $GLOBALS['TL_DCA']['tl_member']['fields'][$excludedProperty]['exclude'] = false;
        }

        Input::setPost('FORM_SUBMIT', 'tl_member');
        Input::setPost('FORM_FIELDS', $this->prepareFormFields($result, $dcTable));

        foreach ($data as $property => $value) {
            if ($property === 'password') {
                Input::setPost('password_confirm', $value);
            }

            Input::setPost($property, $value);
        }

        Input::setGet('rt', RequestToken::get());

        if (!\count($result)) {
            $dcTable->create(['email' => $data['email']]);
        }

        if (\count($result)) {
            Input::setPost('email', $result['email']);

            $dcTable->edit($result['id']);
        }
    }

    /**
     * Prepare form fields form dc table.
     *
     * @param array $result        The member result.
     *
     * @param DC_Table        $dataContainer The data container.
     *
     * @return array The form fields.
     */
    protected function prepareFormFields(array $result, DC_Table $dataContainer)
    {
        if (isset($result['id']) && $result['id']) {
            $dataContainer->id = $result['id'];
        }

        $formFields = $dataContainer->getPalette();

        return (array) $formFields;
    }
}
