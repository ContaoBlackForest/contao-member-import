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

use Contao\BackendMain;
use Contao\BackendTemplate;
use Contao\CoreBundle\Framework\ContaoFramework;
use Doctrine\DBAL\Connection;
use Symfony\Component\Asset\Packages;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Twig\Environment;

/**
 * This trait has common method for process the backend.
 */
trait BackendTrait
{
    /**
     * The contao frame work.
     *
     * @var ContaoFramework
     */
    private $framework;

    /**
     * The database connection.
     *
     * @var Connection
     */
    private $connection;

    /**
     * The request stack.
     *
     * @var RequestStack
     */
    private $requestStack;

    /**
     * The router.
     *
     * @var RouterInterface
     */
    private $router;

    /**
     * The session.
     *
     * @var SessionInterface
     */
    private $session;

    /**
     * The translator.
     *
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * The twig engine.
     *
     * @var Environment
     */
    private $twig;

    /**
     * The asset package.
     *
     * @var Packages
     */
    private $assetPackage;

    /**
     * The event dispatcher.
     *
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * The kernel packages.
     *
     * @var array
     */
    private $kernelPackages;

    /**
     * The kernel project directory.
     *
     * @var string
     */
    private $projectDir;

    /**
     * The session key.
     *
     * @var string
     */
    private $sessionKey;

    /**
     * The backend main.
     *
     * @var BackendMain
     */
    private $backendMain;

    /**
     * The backend template.
     *
     * @var BackendTemplate
     */
    private $backendTemplate;

    /**
     * The constructor.
     *
     * @param ContaoFramework          $framework      The contao frame work.
     * @param Connection               $connection     The database connection.
     * @param RequestStack             $requestStack   The request stack.
     * @param RouterInterface          $router         The router.
     * @param SessionInterface         $session        The session.
     * @param TranslatorInterface      $translator     The translator.
     * @param Environment              $twig           The twig engine.
     * @param Packages                 $assetPackage   The asset package.
     * @param EventDispatcherInterface $dispatcher     The event dispatcher.
     * @param array                    $kernelPackages The kernel packages.
     * @param string                   $projectDir     The kernel project directory.
     * @param string                   $sessionKey     The session key
     */
    public function __construct(
        ContaoFramework $framework,
        Connection $connection,
        RequestStack $requestStack,
        RouterInterface $router,
        SessionInterface $session,
        TranslatorInterface $translator,
        Environment $twig,
        Packages $assetPackage,
        EventDispatcherInterface $dispatcher,
        array $kernelPackages,
        string $projectDir,
        string $sessionKey
    ) {
        $this->framework      = $framework;
        $this->connection     = $connection;
        $this->requestStack   = $requestStack;
        $this->router         = $router;
        $this->session        = $session;
        $this->translator     = $translator;
        $this->twig           = $twig;
        $this->dispatcher     = $dispatcher;
        $this->assetPackage   = $assetPackage;
        $this->kernelPackages = $kernelPackages;
        $this->projectDir     = $projectDir;
        $this->sessionKey     = $sessionKey;
    }

    /**
     * Process the import.
     *
     * @return Response
     */
    abstract public function process(): Response;

    /**
     * Do process.
     *
     * @return Response
     *
     * @throws \ReflectionException If the class or method does not exist.
     */
    private function doProcess(): Response
    {
        $reflection = new \ReflectionMethod(\get_class($this->backendMain), 'output');
        $reflection->setAccessible(true);
        return $reflection->invoke($this->backendMain);
    }

    /**
     * Add the style sheet to the template.
     *
     * @param string $path The path of the style sheet.
     *
     * @return void
     */
    private function addStyleSheet(string $path): void
    {
        $assetPath = $this->assetPackage->getUrl($path, 'black_forest_member_import');
        if (!$this->backendTemplate->stylesheets) {
            $this->backendTemplate->stylesheets = '<link rel="stylesheet" href="' . $assetPath . '">';

            return;
        }

        $this->backendTemplate->stylesheets .= PHP_EOL . '<link rel="stylesheet" href="' . $assetPath . '">';
    }

    /**
     * Add the javascript to the template.
     *
     * @param string $path The path of the javascript.
     *
     * @return void
     */
    private function addJavascript(string $path): void
    {
        $assetPath = $this->assetPackage->getUrl($path, 'black_forest_member_import');
        if (!$this->backendTemplate->javascripts) {
            $this->backendTemplate->javascripts = '<script src="' . $assetPath . '"></script>';

            return;
        }

        $this->backendTemplate->javascripts .= PHP_EOL . '<script src="' . $assetPath . '"></script>';
    }

    /**
     * Setup the backend template.
     *
     * @param string $templateName The template name.
     *
     * @return void
     *
     * @throws \ReflectionException If the class or property does not exist.
     */
    private function setupBackendTemplate(string $templateName): void
    {
        $this->backendTemplate =
            $this->framework->createInstance(BackendTemplate::class, [$templateName]);

        $reflection = new \ReflectionProperty(\get_class($this->backendMain), 'Template');
        $reflection->setAccessible(true);
        $reflection->setValue($this->backendMain, $this->backendTemplate);

        $packages = $this->kernelPackages;

        $this->backendTemplate->version =
            $this->translator->trans('MSC.version', [], 'contao_default') . ' ' . $packages['contao/core-bundle'];

        $this->backendTemplate->main  = '';
        $this->backendTemplate->theme = 'flexible';
    }

    /**
     * Setup the backend main.
     *
     * @return void
     */
    private function setupBackendMain(): void
    {
        $this->backendMain = $this->framework->createInstance(BackendMain::class);
    }
}
