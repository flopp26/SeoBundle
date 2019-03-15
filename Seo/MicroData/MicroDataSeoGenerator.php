<?php

namespace Leogout\Bundle\SeoBundle\Seo\MicroData;

use Leogout\Bundle\SeoBundle\Builder\MicroDataBuilder;
use Leogout\Bundle\SeoBundle\Builder\TagBuilder;
use Leogout\Bundle\SeoBundle\Model\MetaTag;
use Leogout\Bundle\SeoBundle\Model\TitleTag;
use Leogout\Bundle\SeoBundle\Seo\AbstractSeoGenerator;
use Leogout\Bundle\SeoBundle\Seo\Stdlib\PaginationAwareInterface;
use Leogout\Bundle\SeoBundle\Seo\Stdlib\ResourceInterface;
use Leogout\Bundle\SeoBundle\Seo\TitleSeoInterface;
use Leogout\Bundle\SeoBundle\Seo\DescriptionSeoInterface;
use Leogout\Bundle\SeoBundle\Seo\KeywordsSeoInterface;

/**
 * Description of BasicSeoGenerator.
 *
 * @author: leogout
 */
class MicroDataSeoGenerator extends AbstractSeoGenerator
{
    protected $microDataBuilder;

    /**
     * BasicSeoBuilder constructor.
     *
     * @param TagBuilder $tagBuilder
     */
    public function __construct(TagBuilder $tagBuilder, MicroDataBuilder $microDataBuilder)
    {
        parent::__construct($tagBuilder);

        $this->microDataBuilder = $microDataBuilder;
    }

    public function getSocialProfile()
    {
        return $this->microDataBuilder->getSocialProfile();
    }

    public function setSocialProfile($facebookPage, $name)
    {
        $this->microDataBuilder->setSocialProfile($facebookPage, $name);

        return $this;
    }
}
