<?php

namespace Leogout\Bundle\SeoBundle\Twig;

use Leogout\Bundle\SeoBundle\Builder\ImageBuilder;
use Leogout\Bundle\SeoBundle\Builder\TagBuilder;
use Leogout\Bundle\SeoBundle\Model\RenderableInterface;
use Leogout\Bundle\SeoBundle\Provider\SeoGeneratorProvider;

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
     * SeoExtension constructor.
     *
     * @param SeoGeneratorProvider $generatorProvider
     * @param TagBuilder $tagBuilder
     */
    public function __construct(SeoGeneratorProvider $generatorProvider, TagBuilder $tagBuilder, ImageBuilder $imageBuilder)
    {
        $this->tagBuilder = $tagBuilder;
        $this->imageBuilder = $imageBuilder;
        $this->generatorProvider = $generatorProvider;
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

    public function seoPage($pageName, $addSufix = true, $options = null)
    {
        $generators = $this->generatorProvider->getAll();
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

            $generator->setPage($pageName, $addSufix);

            if($options){
                foreach($options as $method=>$value){
                    $methodName = sprintf('set%s', ucfirst($method));
                    if (method_exists($generator, $methodName)) {
                        $generator->$methodName($value);
                    }
                }
            }
        }

        return $this->tagBuilder->render();
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
