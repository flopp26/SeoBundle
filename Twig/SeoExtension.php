<?php

namespace Leogout\Bundle\SeoBundle\Twig;

use Leogout\Bundle\SeoBundle\Builder\ImageBuilder;
use Leogout\Bundle\SeoBundle\Builder\TagBuilder;
use Leogout\Bundle\SeoBundle\Model\RenderableInterface;
use Leogout\Bundle\SeoBundle\Provider\SeoGeneratorProvider;
use Knp\Menu\Twig\Helper as KnpMenuHelper;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Description of SeoExtension.
 *
 * @author: leogout
 */
class SeoExtension extends \Twig_Extension
{
    /**
     * @var TagBuilder
     */
    private $tagBuilder;

    /**
     * @var ImageBuilder
     */
    private $imageBuilder;

    /**
     * @var SeoGeneratorProvider
     */
    private $generatorProvider;

    /**
     * @var KnpMenuHelper
     */
    private $menuHelper;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * SeoExtension constructor.
     *
     * @param SeoGeneratorProvider $generatorProvider
     * @param TagBuilder $tagBuilder
     */
    public function __construct(SeoGeneratorProvider $generatorProvider, TagBuilder $tagBuilder, ImageBuilder $imageBuilder, KnpMenuHelper $menuHelper, RequestStack $requestStack, TranslatorInterface $translator)
    {
        $this->tagBuilder = $tagBuilder;
        $this->imageBuilder = $imageBuilder;
        $this->generatorProvider = $generatorProvider;
        $this->menuHelper = $menuHelper;
        $this->requestStack = $requestStack;
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('seo_page', [$this, 'seoPage'], ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('leogout_seo', [$this, 'seo'], ['is_safe' => ['html']]),
        );
    }

    /**
     * @param $alias
     *
     * @return string
     */
    public function seo($alias = null)
    {
        foreach ($this->generatorProvider->getAll() as $configurator) {
        }
        return $this->tagBuilder->render();
    }

    public function seoPage($pageName = null, $addSufix = true, $options = null)
    {
        $generators = $this->generatorProvider->getAll();
        if(is_null($pageName) == false ) {
            foreach ($generators as $generator) {

                /* on vérifie si l'image existe */
                if ($imageDatas = $this->imageBuilder->imagePageAvailable($pageName)) {
                    if (method_exists($generator, 'setImage')) {
                        $generator->setImage($imageDatas['url']);
                        if (method_exists($generator, 'setImageType')) {
                            $generator->setImageType($imageDatas['mime']);
                            $generator->setImageWidth($imageDatas['width']);
                            $generator->setImageHeight($imageDatas['height']);
                        }
                    }
                }

                if ($options) {
                    foreach ($options as $method => $value) {
                        $methodName = sprintf('set%s', ucfirst($method));
                        if (method_exists($generator, $methodName)) {
                            $generator->$methodName($value);
                        }
                    }
                }

                $generator->setPage($pageName, $addSufix);
            }
        }

        return $this->tagBuilder->render() . PHP_EOL . $this->generateBreadcrumbMarkup();
    }

    private function generateBreadcrumbMarkup()
    {
        if($currentItem = $this->menuHelper->getCurrentItem('website')){

            $root = array(
                '@context' => 'https://schema.org',
                '@type' => 'BreadcrumbList',
                'itemListElement' => array()
            );

            foreach($this->menuHelper->getBreadcrumbsArray($currentItem) as $index => $breadcrumb){
                $root['itemListElement'][] =  array(
                    '@type' => 'ListItem',
                    'position' => $index + 1,
                    'name' => $this->translator->trans($breadcrumb['item']->getName()),
                    'item' => $this->requestStack->getCurrentRequest()->getSchemeAndHttpHost() . $breadcrumb['item']->getUri()
                );
            }

            return '<script type="application/ld+json">'. json_encode($root) .'</script>';
        }

        return;
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'leogout_seo.twig.seo_extension';
    }
}
