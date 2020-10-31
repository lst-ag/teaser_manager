<?php
declare(strict_types = 1);

/***
 *
 * This file is part of the "Teaser Manager" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2016 Christian Fries <christian.fries@lst.team>
 *
 ***/

namespace LST\TeaserManager\Controller;

use LST\BackendModule\Controller\BackendModuleActionController;
use LST\BackendModule\Domain\Session\BackendSession;
use LST\TeaserManager\Domain\Dto\Filter;
use LST\TeaserManager\Domain\Repository\TeaserLayoutRepository;
use LST\TeaserManager\Domain\Repository\TeaserRepository;
use LST\TeaserManager\Domain\Repository\TeaserTypeRepository;
use TYPO3\CMS\Backend\View\BackendTemplateView;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;

class AdminController extends BackendModuleActionController
{
    /**
     * @var BackendSession
     */
    protected $backendSession;

    /**
     * @var TeaserLayoutRepository
     */
    protected $teaserLayoutRepository;

    /**
     * @var TeaserRepository
     */
    protected $teaserRepository;

    /**
     * @var TeaserTypeRepository
     */
    protected $teaserTypeRepository;

    public function injectBackendSession(BackendSession $backendSession)
    {
        $this->backendSession = $backendSession;
    }

    public function injectTeaserLayoutRepository(TeaserLayoutRepository $teaserLayoutRepository)
    {
        $this->teaserLayoutRepository = $teaserLayoutRepository;
    }

    public function injectTeaserTypeRepository(TeaserTypeRepository $teaserTypeRepository)
    {
        $this->teaserTypeRepository = $teaserTypeRepository;
    }

    public function injectTeaserRepository(TeaserRepository $teaserRepository)
    {
        $this->teaserRepository = $teaserRepository;
    }

    /**
     * @param ViewInterface $view
     * @return void
     */
    protected function initializeView(ViewInterface $view)
    {
        /** @var BackendTemplateView $view */
        parent::initializeView($view);

        $this->pageRenderer->loadRequireJsModule('TYPO3/CMS/TeaserManager/AdministrationModule');
    }

    /**
     * Function will be called before every other action
     *
     * @return void
     */
    public function initializeAction()
    {
        // Initialize configuration
        $this->extKey = 'teaser_manager';
        $this->moduleName = 'web_TeaserManagerAdmin';

        $this->backendSession->setStorageKey($this->extKey);

        $extConf = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get($this->extKey);
        $this->pageUid = intval($extConf['globalStoragePid']);

        parent::initializeAction();

        // Define menu items
        $this->setMenuIdentifier('teaserMenu');
        $menuItems = [
            [
                'label' => $this->getLanguageService()->sL('LLL:EXT:teaser_manager/Resources/Private/Language/locallang.xlf:teaser.teasers'),
                'action' => 'listTeaser',
                'controller' => 'Admin'
            ]
        ];

        if (GeneralUtility::inList($this->getBackendUser()->groupData['tables_modify'], 'tx_teasermanager_domain_model_teasertype') || $this->getBackendUser()->isAdmin()) {
            $menuItems[] = [
                'label' => $this->getLanguageService()->sL('LLL:EXT:teaser_manager/Resources/Private/Language/locallang.xlf:teasertype.teasertypes'),
                'action' => 'listTeaserType',
                'controller' => 'Admin'
            ];
        }

        if (GeneralUtility::inList($this->getBackendUser()->groupData['tables_modify'], 'tx_teasermanager_domain_model_teaserlayout') || $this->getBackendUser()->isAdmin()) {
            $menuItems[] = [
                'label' => $this->getLanguageService()->sL('LLL:EXT:teaser_manager/Resources/Private/Language/locallang.xlf:teaserlayout.teaserlayouts'),
                'action' => 'listTeaserLayout',
                'controller' => 'Admin'
            ];
        }
        $this->setMenuItems($menuItems);

        // Define toolbar buttons
        $buttons = [
            $this->createNewRecordButton(
                'tx_teasermanager_domain_model_teaser',
                $this->getLanguageService()->sL('LLL:EXT:teaser_manager/Resources/Private/Language/locallang.xlf:teaser.new'),
                [
                    'Admin' => ['listTeaser']
                ]
            ),
            $this->createJsButton(
                $this->getLanguageService()->sL('LLL:EXT:teaser_manager/Resources/Private/Language/locallang.xlf:administration.toggleForm'),
                $this->iconFactory->getIcon('actions-filter', Icon::SIZE_SMALL),
                [
                    'togglelink' => '1',
                    'toggle' => 'tooltip',
                    'placement' => 'bottom',
                ],
                [
                    'Admin' => ['listTeaser']
                ]
            ),
            $this->createNewRecordButton(
                'tx_teasermanager_domain_model_teasertype',
                $this->getLanguageService()->sL('LLL:EXT:teaser_manager/Resources/Private/Language/locallang.xlf:teasertype.new'),
                [
                    'Admin' => ['listTeaserType']
                ],
                [
                    'action' => 'listTeaserType',
                    'controller' => 'Admin'
                ]
            ),
            $this->createNewRecordButton(
                'tx_teasermanager_domain_model_teaserlayout',
                $this->getLanguageService()->sL('LLL:EXT:teaser_manager/Resources/Private/Language/locallang.xlf:teaserlayout.new'),
                [
                    'Admin' => ['listTeaserLayout']
                ],
                [
                    'action' => 'listTeaserLayout',
                    'controller' => 'Admin'
                ]
            ),
            $this->createClipboardButton('tx_teasermanager_domain_model_teaser')
        ];
        $this->setButtons($buttons);
    }

    /**
     * @return void
     */
    public function listTeaserTypeAction()
    {
        $teaserTypes = $this->teaserTypeRepository->findAll();
        $this->view->assign('teaserTypes', $teaserTypes);
    }

    /**
     * @return void
     */
    public function listTeaserLayoutAction()
    {
        $teaserLayouts = $this->teaserLayoutRepository->findAll();
        $this->view->assign('teaserLayouts', $teaserLayouts);
    }

    /**
     * @param \LST\TeaserManager\Domain\Dto\Filter $filter
     * @return void
     */
    public function listTeaserAction($filter = null)
    {
        // Add filtering to records
        if ($filter === null) {
            // Get filter from session if available
            $filter = $this->backendSession->get('filter');
            if (!$filter instanceof Filter) {
                // No filter available, create new one
                $filter = new Filter();
            }
        } else {
            $this->backendSession->store('filter', $filter);
        }
        $this->view->assign('filter', $filter);

        if ($filter->getType()) {
            $teasers = $this->teaserRepository->findByType($filter->getType(), 'Backend');
        } else {
            $teasers = $this->teaserRepository->findAll('Backend');
        }
        $this->view->assign('teasers', $teasers);

        $teaserTypes = $this->teaserTypeRepository->findAll('Backend');
        $this->view->assign('teaserTypes', $teaserTypes);
    }
}
