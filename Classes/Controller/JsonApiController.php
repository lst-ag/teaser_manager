<?php
declare(strict_types = 1);

namespace LST\TeaserManager\Controller;

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

use LST\TeaserManager\Domain\Model\Teaser;
use LST\TeaserManager\Domain\Model\TeaserType;
use LST\TeaserManager\Domain\Repository\TeaserRepository;
use LST\TeaserManager\Domain\Repository\TeaserTypeRepository;
use LST\TeaserManager\Mvc\View\JsonView;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\Exception\StopActionException;
use TYPO3\CMS\Extbase\Service\ImageService;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\Service\TypoLinkCodecService;

class JsonApiController extends ActionController
{
    /**
     * @var JsonView
     */
    protected $view;

    /**
     * @var string
     */
    protected $defaultViewObjectName = JsonView::class;

    /**
     * @var ImageService
     */
    protected $imageService;

    /**
     * @var TeaserRepository
     */
    protected $teaserRepository;

    /**
     * @var TeaserTypeRepository
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
     * @var bool
     */
    protected $processApiImage = false;

    /**
     * @var array
     */
    protected $apiImageProcessingInstructions = [];

    public function __construct(ImageService $imageService, TeaserRepository $teaserRepository, TeaserTypeRepository $teaserTypeRepository)
    {
        $this->imageService = $imageService;
        $this->teaserRepository = $teaserRepository;
        $this->teaserTypeRepository = $teaserTypeRepository;
    }

    /**
     * Resolves and checks the current action method name
     *
     * @throws StopActionException
     */
    protected function resolveActionMethodName(): string
    {
        switch ($this->request->getMethod()) {
            case 'HEAD':
            case 'OPTIONS':
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

    public function initializeAction()
    {
        $extensionConfiguration = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('teaser_manager');
        $this->apiSecret = (!empty($extensionConfiguration['apiSecret'])) ? $extensionConfiguration['apiSecret'] : 'AfcH5CzAgc27WPyP';
        $this->baseUrl = $extensionConfiguration['baseUrl'];

        if ($extensionConfiguration['processApiImage']) {
            $processingInstructions = [];

            if (!empty($extensionConfiguration['apiImageWidth'])) {
                $processingInstructions['width'] = $extensionConfiguration['apiImageWidth'];
            }

            if (!empty($extensionConfiguration['apiImageHeight'])) {
                $processingInstructions['height'] = $extensionConfiguration['apiImageHeight'];
            }

            $this->processApiImage = true;
            $this->apiImageProcessingInstructions = $processingInstructions;
        }
    }

    /**
     * Return meta information
     */
    public function headAction()
    {
        return;
    }

    /**
     * @throws StopActionException
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
                $image = $this->imageService->getImage('', $teaser->getImage(), true);

                if ($this->processApiImage) {
                    $processedImage = $this->imageService->applyProcessingInstructions($image, $this->apiImageProcessingInstructions);
                    $imageUri = $this->imageService->getImageUri($processedImage, true);
                } else {
                    $imageUri = $this->imageService->getImageUri($image, true);
                }

                $teaser->setPublicImageUrl($imageUri);
            }
        }
        $this->view->assign('teasers', $teasers);

        $this->view->setVariablesToRender(['teasers']);
    }

    /**
     * Throw error status with JSON message
     * @param $status
     * @param $message
     * @throws StopActionException
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
     * @throws StopActionException
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

    public static function renderLinkFromTypoLink(array $arguments): string
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
