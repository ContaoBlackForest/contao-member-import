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

namespace ContaoBlackForest\MemberImportBundle\Callback\DataContainer\Table\Member;

use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Twig\Environment;

/**
 * Build the import menu as nested global operations.
 */
final class BuildImportMenu
{
    /**
     * The session.
     *
     * @var SessionInterface
     */
    private $session;

    /**
     * The twig environment.
     *
     * @var Environment
     */
    private $twig;

    /**
     * The database connection.
     *
     * @var Connection
     */
    private $connection;

    /**
     * The member import session key.
     *
     * @var string
     */
    private $sessionKey;

    public function __construct(
        SessionInterface $session,
        Environment $twig,
        Connection $connection,
        string $sessionKey
    ) {
        $this->session    = $session;
        $this->twig       = $twig;
        $this->connection = $connection;
        $this->sessionKey = $sessionKey;
    }

    /**
     * Inject the import menu.
     *
     * @param string $href  string The href.
     * @param string $label string The label.
     * @param string $title string The title.
     * @param string $class string The class.
     *
     * @return string
     */
    public function __invoke(string $href, string $label, string $title, string $class): string
    {
        $this->session->remove($this->sessionKey);

        return $this->twig->render(
            '@BlackForestMemberImport/Backend/be_nested_import_menu.html.twig',
            [
                'menuClass'         => $class,
                'label'             => $label,
                'importInformation' => $this->generateImportInformation()
            ]
        );
    }

    /**
     * Generate the import information.
     *
     * @return string|null The import information.
     */
    private function generateImportInformation(): ?string
    {
        $platform = $this->connection->getDatabasePlatform();
        $builder  = $this->connection->createQueryBuilder();
        $builder
            ->select(
                $platform->quoteIdentifier('id'),
                $platform->quoteIdentifier('title')
            )
            ->from($platform->quoteIdentifier('tl_member_import'))
            ->where($builder->expr()->neq($platform->quoteIdentifier('disable_import'), ':disable_import'))
            ->orderBy($platform->quoteIdentifier('title'))
            ->setParameter(':disable_import', 1);

        $statement = $builder->execute();
        if (!$statement->rowCount()) {
            return null;
        }

        $result = $statement->fetchAll(\PDO::FETCH_OBJ);

        $informationRow = '  &bull; %s (ID %s) \n';
        $information    = '';

        foreach ($result as $item) {
            $information .= \sprintf($informationRow, $item->title, $item->id);
        }

        return $information;
    }
}
