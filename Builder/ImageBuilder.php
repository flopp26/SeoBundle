<?php

namespace Leogout\Bundle\SeoBundle\Builder;

use Leogout\Bundle\SeoBundle\Factory\TagFactory;
use Leogout\Bundle\SeoBundle\Model\LinkTag;
use Leogout\Bundle\SeoBundle\Model\MetaTag;
use Leogout\Bundle\SeoBundle\Model\RenderableInterface;
use Leogout\Bundle\SeoBundle\Model\TitleTag;
use Symfony\Bundle\FrameworkBundle\Templating\Helper\AssetsHelper;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * Description of TagBuilder.
 *
 * @author: leogout
 */
class ImageBuilder
{
    const TYPE_PAGE = 'pages';
    const TYPE_ARTICLE = 'articles';
    const TYPE_ARTICLE_CATEGORY = 'articles-category';

    /**
     * @var KernelInterface
     */
    private $kernel;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var \Symfony\Component\Asset\Packages
     */
    private $assetsManager;

    public function __construct(KernelInterface $kernel, RequestStack $requestStack, \Symfony\Component\Asset\Packages $assetsManager)
    {
        $this->kernel = $kernel;
        $this->requestStack = $requestStack;
        $this->assetsManager = $assetsManager;
    }

    private function getImage($pageName, $type, $firstRecursive = true)
    {
        $pageName = str_replace(array('_', '.'), '-', $pageName);
        $folderImage = join(DIRECTORY_SEPARATOR, array($this->kernel->getProjectDir(), 'assets', 'images', $type, $pageName));
        $list = glob(sprintf('%s/%s.{jpg,gif,png}', $folderImage, 'seo-image-' . $this->requestStack->getCurrentRequest()->getLocale()), GLOB_BRACE);

        // recherche sans la langue
        if (count($list) == 0) {
            $list = glob(sprintf('%s/%s.{jpg,gif,png}', $folderImage, 'seo-image'), GLOB_BRACE);
        }

        // get default image
        if ($firstRecursive && count($list) == 0) {
            return $this->getImage(null, $type, false);
        }

        if (count($list) > 0) {
            $imageSize = \getimagesize($list[0]);
            if ($request = $this->requestStack->getCurrentRequest()) {
                $url =  $this->assetsManager->getUrl(join(DIRECTORY_SEPARATOR, array('images', $type, $pageName, basename($list[0]))));
                return array(
                    'url' => $request->getSchemeAndHttpHost() . str_replace('//', '/', $url),
                    'mime' => $imageSize['mime'],
                    'width' => $imageSize[0],
                    'height' => $imageSize[1]
                );
            }
        }

        return false;
    }

    /**
     * @param $id = nom de la page ou nom de l'article
     * @param $type = TYPE_PAGE ou TYPE_ARTICLE
     * @return array|bool
     */
    public function imagePageAvailable($id, $type)
    {
        return $this->getImage($id, $type);
    }
}
