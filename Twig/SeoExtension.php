<?php

namespace Leogout\Bundle\SeoBundle\Twig;

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
     * @var SeoGeneratorProvider
     */
    private $generatorProvider;

    /**
     * SeoExtension constructor.
     *
     * @param SeoGeneratorProvider $generatorProvider
     * @param TagBuilder $tagBuilder
     */
    public function __construct(SeoGeneratorProvider $generatorProvider, TagBuilder $tagBuilder)
    {
        $this->tagBuilder = $tagBuilder;
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

    public function seoPage($pageName, $image = null)
    {
        $this->generatorProvider->setPage($pageName, $image);

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
