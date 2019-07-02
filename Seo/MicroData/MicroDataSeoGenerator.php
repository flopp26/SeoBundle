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

    public function setFaqs(array $faqs)
    {
        $this->microDataBuilder->setFaqs($faqs);

        return $this;
    }

    public function setRating($rating)
    {
        $this->microDataBuilder->setRating($rating);

        return $this;
    }
    
    public function setOffers($faq)
    {
        $this->microDataBuilder->setOffers($faq);

        return $this;
    }

    public function setOrganization($logo, $phone, $email, $name, $sameAs)
    {
        $this->microDataBuilder->setOrganization($logo, $phone, $email, $name, $sameAs);

        return $this;
    }
}
