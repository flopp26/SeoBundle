<?php

namespace Leogout\Bundle\SeoBundle\Seo;

use Leogout\Bundle\SeoBundle\Builder\TagBuilder;
use Leogout\Bundle\SeoBundle\Model\RenderableInterface;

/**
 * Description of AbstractSeoGenerator.
 *
 * @author: leogout
 */
abstract class AbstractSeoGenerator implements RenderableInterface
{
    /**
     * @var string
     */
    protected $suffix;

    /**
     * @var string
     */
    protected $separator;

    /**
     * @var TagBuilder
     */
    protected $tagBuilder;

    /**
     * BasicSeoBuilder constructor.
     *
     * @param TagBuilder $tagBuilder
     */
    public function __construct(TagBuilder $tagBuilder)
    {
        $this->tagBuilder = $tagBuilder;
    }

    public function setPage($pageName, $addSufix, $image = null)
    {
        if (method_exists($this, 'setTitle')) {
            $this->setTitle(sprintf('page.%s.seo.title|trans', $pageName), $addSufix);
        }

        if (method_exists($this, 'setDescription')) {
            $this->setDescription(sprintf('page.%s.seo.description|trans', $pageName));
        }

        if (method_exists($this, 'setKeywords')) {
            $this->setKeywords(sprintf('page.%s.seo.keywords|trans', $pageName));
        }

        if (null != $image && method_exists($this, 'setImage')) {
            $this->setImage($image);
        }
    }

    /**
     * @return string
     */
    public function render()
    {
        return $this->tagBuilder->render();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }
}
