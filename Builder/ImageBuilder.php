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

    private function getImage($pageName)
    {
        $pageName = str_replace('_', '-', $pageName);
        $folderImage = join(DIRECTORY_SEPARATOR, array($this->kernel->getProjectDir(), 'assets', 'images', 'pages', $pageName));

        $list = glob(sprintf('%s/%s.{jpg,gif,png}', $folderImage, 'seo-image'), GLOB_BRACE);
        if (count($list) > 0) {

            if ($request = $this->requestStack->getCurrentRequest()) {
                return $request->getSchemeAndHttpHost() . $this->assetsManager->getUrl(join(DIRECTORY_SEPARATOR, array('images', 'pages', $pageName, basename($list[0]))));
            }
        }

        return false;
    }

    public function imagePageAvailable($pageName)
    {
        return $this->getImage($pageName);
    }
}
