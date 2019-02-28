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

    protected $suffix;

    protected $separator;

    public function __construct(SeoTranslator $translator, RequestStack $request, $suffix, $separator)
    {
        $this->translator = $translator;
        $this->request = $request;
        $this->suffix = $suffix;
        $this->separator = $separator;
    }

    /**
     * @return TitleTag
     */
    public function createTitle($addSuffix)
    {
        $titleTag = new TitleTag($this->translator, $addSuffix, $this->suffix, $this->separator);

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
