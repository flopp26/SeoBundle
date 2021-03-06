<?php

namespace Leogout\Bundle\SeoBundle\Twig;

use Leogout\Bundle\SeoBundle\Builder\ImageBuilder;
use Leogout\Bundle\SeoBundle\Builder\MicroDataBuilder;
use Leogout\Bundle\SeoBundle\Builder\TagBuilder;
use Leogout\Bundle\SeoBundle\Model\RatingBuilderInterface;
use Leogout\Bundle\SeoBundle\Provider\SeoGeneratorProvider;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Description of SeoExtension.
 *
 * @author: leogout
 */
class SeoExtension extends AbstractExtension
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
     * @var MicroDataBuilder
     */
    private $microDataBuilder;

    /**
     * @var RatingBuilderInterface
     */
    private $ratingBuilder;

    /**
     * SeoExtension constructor.
     *
     * @param SeoGeneratorProvider $generatorProvider
     * @param TagBuilder $tagBuilder
     */
    public function __construct(SeoGeneratorProvider $generatorProvider, TagBuilder $tagBuilder, ImageBuilder $imageBuilder, MicroDataBuilder $microDataBuilder, RatingBuilderInterface $ratingBuilder)
    {
        $this->tagBuilder = $tagBuilder;
        $this->imageBuilder = $imageBuilder;
        $this->generatorProvider = $generatorProvider;
        $this->microDataBuilder = $microDataBuilder;
        $this->ratingBuilder = $ratingBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new TwigFunction('seo_article', [$this, 'seoArticle'], ['is_safe' => ['html']]),
            new TwigFunction('seo_article_category', [$this, 'seoArticleCategory'], ['is_safe' => ['html']]),
            new TwigFunction('seo_page', [$this, 'seoPage'], ['is_safe' => ['html']]),
            new TwigFunction('leogout_seo', [$this, 'seo'], ['is_safe' => ['html']]),
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

    public function seoArticleCategory($category, $options = null)
    {
        return $this->seoArticle($category, $options, ImageBuilder::TYPE_ARTICLE_CATEGORY);
    }

    public function seoArticle($article, $options = null, $type = ImageBuilder::TYPE_ARTICLE)
    {
        $generators = $this->generatorProvider->getAll();
        foreach ($generators as $generator) {

            if (method_exists($generator, 'setTitle')) {
                $generator->setTitle($article->getSeoTitle(), true);
            }

            if (method_exists($generator, 'setDescription')) {
                $generator->setDescription($article->getSeoDescription());
            }

            if (method_exists($generator, 'setKeywords')) {
                $generator->setKeywords(null);
            }

            if (method_exists($generator, 'setType')) {
                $generator->setType('article');
            }

            if (method_exists($generator, 'setPublisher') && isset($options['publisher'])) {
                $generator->setPublisher($options['publisher']);
            }

            /* on vérifie si l'image existe */
            if(method_exists($article, 'getKey')) {

                if ($imageDatas = $this->imageBuilder->imagePageAvailable($article->getKey(), $type)) {
                    if (method_exists($generator, 'setImage')) {
                        $generator->setImage($imageDatas['url']);
                        if (method_exists($generator, 'setImageType')) {
                            $generator->setImageType($imageDatas['mime']);
                            $generator->setImageWidth($imageDatas['width']);
                            $generator->setImageHeight($imageDatas['height']);
                        }
                    }
                }
            }
        }

        return $this->tagBuilder->render() . PHP_EOL . $this->microDataBuilder->render($this->ratingBuilder);
    }

    public function seoPage($pageName = null, $addSufix = true, $options = null)
    {
        $generators = $this->generatorProvider->getAll();

        if (is_null($pageName) == false) {
            foreach ($generators as $generator) {

                /* on vérifie si l'image existe */
                if ($imageDatas = $this->imageBuilder->imagePageAvailable($pageName, ImageBuilder::TYPE_PAGE)) {
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
                
                if ($options) {
                    foreach ($options as $method => $value) {
                        $methodName = sprintf('set%s', ucfirst($method));
                        if (method_exists($generator, $methodName)) {
                            $generator->$methodName($value);
                        }
                    }
                } 
            }
        }

        return $this->tagBuilder->render() . PHP_EOL . $this->microDataBuilder->render($this->ratingBuilder);
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
