<?php

namespace Leogout\Bundle\SeoBundle\Factory;

use Leogout\Bundle\SeoBundle\Model\LinkTag;
use Leogout\Bundle\SeoBundle\Model\MetaTag;
use Leogout\Bundle\SeoBundle\Model\TitleTag;
use Leogout\Bundle\SeoBundle\Seo\SeoTranslator;
use Symfony\Component\HttpFoundation\RequestStack;

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

    /**
     * @var RequestStack
     */
    protected $request;

    public function __construct(SeoTranslator $translator, RequestStack $request)
    {
        $this->translator = $translator;
        $this->request = $request;
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
        $metaTag = new MetaTag($this->translator, $this->request);

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
