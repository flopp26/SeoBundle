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

    public function setPage($pageName)
    {
        if (method_exists($this, 'setTitle')) {
            $this->setTitle(sprintf('%s.seo.title|trans', $pageName));
        }

        if (method_exists($this, 'setDescription')) {
            $this->setDescription(sprintf('%s.seo.description|trans', $pageName));
        }

        if (method_exists($this, 'setKeywords')) {
            $this->setKeywords(sprintf('%s.seo.keywords|trans', $pageName));
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
