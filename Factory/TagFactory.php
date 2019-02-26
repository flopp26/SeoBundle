<?php

namespace Leogout\Bundle\SeoBundle\Factory;

use Leogout\Bundle\SeoBundle\Model\LinkTag;
use Leogout\Bundle\SeoBundle\Model\MetaTag;
use Leogout\Bundle\SeoBundle\Model\TitleTag;
use Leogout\Bundle\SeoBundle\Seo\SeoTranslator;

/**
 * Description of TagFactory.
 *
 * @author: leogout
 */
class TagFactory
{
    /**
     * @var SeoTranslator
     */
    protected $translator;

    public function __construct(SeoTranslator $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @return TitleTag
     */
    public function createTitle()
    {
        $titleTag = new TitleTag($this->translator);

        return $titleTag;
    }

    /**
     * @return MetaTag
     */
    public function createMeta()
    {
        $metaTag = new MetaTag($this->translator);

        return $metaTag;
    }

    /**
     * @return LinkTag
     */
    public function createLink()
    {
        $linkTag = new LinkTag();

        return $linkTag;
    }
}
