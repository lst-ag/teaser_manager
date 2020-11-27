<?php
declare(strict_types = 1);

namespace CHF\TeaserManager\Controller;

/***
 *
 * This file is part of the "LWO Job Management" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2017 Christian Fries <christian.fries@lst.team>, LST AG
 *
 ***/

use CHF\TeaserManager\Domain\Model\Teaser;
use CHF\TeaserManager\Domain\Model\TeaserType;
use CHF\TeaserManager\Mvc\View\JsonView;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\Service\TypoLinkCodecService;

class JsonApiController extends ActionController
{
    /**
     * @var \CHF\TeaserManager\Mvc\View\JsonView
     */
    protected $view;

    /**
     * @var string
     */
    protected $defaultViewObjectName = JsonView::class;

    /**
     * @var \CHF\TeaserManager\Domain\Repository\TeaserRepository
     */
    protected $teaserRepository;

    /**
     * @var \CHF\TeaserManager\Domain\Repository\TeaserTypeRepository
     */
    protected $teaserTypeRepository;

    /**
     * @var string
     */
    protected $apiSecret;

    /**
     * @var string
     */
    protected $baseUrl;

    /**
     * @param \CHF\TeaserManager\Domain\Repository\TeaserRepository $teaserRepository
     */
    public function injectTeaserRepository(\CHF\TeaserManager\Domain\Repository\TeaserRepository $teaserRepository)
    {
        $this->teaserRepository = $teaserRepository;
    }

    /**
     * @param \CHF\TeaserManager\Domain\Repository\TeaserTypeRepository $teaserTypeRepository
     */
    public function injectTeaserTypeRepository(\CHF\TeaserManager\Domain\Repository\TeaserTypeRepository $teaserTypeRepository)
    {
        $this->teaserTypeRepository = $teaserTypeRepository;
    }

    /**
     * Resolves and checks the current action method name
     *
     * @return string Method name of the current action
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
     */
    protected function resolveActionMethodName()
    {
        switch ($this->request->getMethod()) {
            case 'HEAD':
                $actionName = 'head';
                break;
            case 'GET':
                $actionName = 'get';
                break;
            case 'POST':
            case 'PUT':
            case 'DELETE':
            default:
                $this->throwStatus(400, 'Bad Request.');
        }
        return $actionName . 'Action';
    }

    /**
     * Set configuration options
     */
    public function initializeAction()
    {
        $extensionConfiguration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['teaser_manager']);
        $this->apiSecret = (!empty($extensionConfiguration['apiSecret'])) ? $extensionConfiguration['apiSecret'] : 'AfcH5CzAgc27WPyP';
        $this->baseUrl = $extensionConfiguration['baseUrl'];
    }

    /**
     * Return meta information
     */
    public function headAction()
    {
        return;
    }

    /**
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\NoSuchArgumentException
     */
    public function getAction()
    {
        $this->denyAccessUnlessGranted();

        if (GeneralUtility::_GET('teaser-type') !== null) {
            /** @var TeaserType $teaserType */
            $teaserType = $this->teaserTypeRepository->findByUid(GeneralUtility::_GET('teaser-type'));
            $teasers = $this->teaserRepository->findByType($teaserType);
        } else {
            $teasers = $this->teaserRepository->findAll();
        }

        /** @var Teaser $teaser */
        foreach ($teasers as $teaser) {
            $teaser->setLink(
                $this->renderLinkFromTypoLink([
                    'parameter' => $teaser->getLink()
                ])
            );
            if ($teaser->getImage() !== null) {
                $teaser->setPublicImageUrl($this->baseUrl);
            }
        }
        $this->view->assign('teasers', $teasers);

        $this->view->setVariablesToRender(['teasers']);
    }

    /**
     * Throw error status with JSON message
     * @param $status
     * @param $message
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
     */
    private function throwErrorStatus($status, $message)
    {
        $data = ['errors' => [
            'status' => $status,
            'message' => $message
        ]];
        $this->throwStatus($status, null, json_encode($data));
    }

    /**
     * Validate access token
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
     */
    private function denyAccessUnlessGranted()
    {
        $authorizationHeader = $_SERVER['HTTP_AUTHORIZATION'];
        if (empty($authorizationHeader)) {
            $authorizationHeader = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
        }
        if ($authorizationHeader !== $this->apiSecret) {
            $this->throwStatus(401, 'You are not allowed to access the api without proper authorization.');
        }
    }

    /**
     * @param array $arguments
     * @return string
     */
    public static function renderLinkFromTypoLink(array $arguments)
    {
        $parameter = $arguments['parameter'];

        $typoLinkCodec = GeneralUtility::makeInstance(TypoLinkCodecService::class);
        $typoLinkConfiguration = $typoLinkCodec->decode($parameter);
        $mergedTypoLinkConfiguration = static::mergeTypoLinkConfiguration($typoLinkConfiguration, $arguments);
        $typoLinkParameter = $typoLinkCodec->encode($mergedTypoLinkConfiguration);

        $content = '';
        if ($parameter) {
            $content = static::invokeContentObjectRenderer($arguments, $typoLinkParameter);
        }
        return $content;
    }

    /**
     * @param array $arguments
     * @param string $typoLinkParameter
     * @return string
     */
    protected static function invokeContentObjectRenderer(array $arguments, string $typoLinkParameter): string
    {
        $addQueryString = $arguments['addQueryString'] ?? false;
        $addQueryStringMethod = $arguments['addQueryStringMethod'] ?? 'GET';
        $addQueryStringExclude = $arguments['addQueryStringExclude'] ?? '';
        $absolute = true;

        $instructions = [
            'parameter' => $typoLinkParameter,
            'forceAbsoluteUrl' => $absolute,
        ];
        if (isset($arguments['language']) && $arguments['language'] !== null) {
            $instructions['language'] = $arguments['language'];
        }
        if ($addQueryString) {
            $instructions['addQueryString'] = $addQueryString;
            $instructions['addQueryString.'] = [
                'method' => $addQueryStringMethod,
                'exclude' => $addQueryStringExclude,
            ];
        }

        $contentObject = GeneralUtility::makeInstance(ContentObjectRenderer::class);
        return $contentObject->typoLink_URL($instructions);
    }

    /**
     * Merges view helper arguments with typolink parts.
     *
     * @param array $typoLinkConfiguration
     * @param array $arguments
     * @return array
     */
    protected static function mergeTypoLinkConfiguration(array $typoLinkConfiguration, array $arguments): array
    {
        if ($typoLinkConfiguration === []) {
            return $typoLinkConfiguration;
        }

        $additionalParameters = $arguments['additionalParams'] ?? '';

        // Combine additionalParams
        if ($additionalParameters) {
            $typoLinkConfiguration['additionalParams'] .= $additionalParameters;
        }

        return $typoLinkConfiguration;
    }
}
