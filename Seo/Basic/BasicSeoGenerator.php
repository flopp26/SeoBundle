<?php

namespace Leogout\Bundle\SeoBundle\Seo\Basic;

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
class BasicSeoGenerator extends AbstractSeoGenerator
{
    /**
     * @param $content
     * @param bool $addSuffix
     * @return $this
     */
    public function setTitle($content, $addSuffix = true)
    {
        if($addSuffix){
            $content = sprintf('%s %s %s', $content, $this->getSeparator(), $this->getSuffix());
        }

        $this->tagBuilder->setTitle($content);

        return $this;
    }

    /**
     * @return TitleTag
     */
    public function getTitle()
    {
        return $this->tagBuilder->getTitle();
    }

    /**
     * @param string $content
     *
     * @return $this
     */
    public function setDescription($content)
    {
        $this->tagBuilder->addMeta('description')
            ->setType(MetaTag::NAME_TYPE)
            ->setTagName('description')
            ->setContent((string) $content);

        return $this;
    }

    /**
     * @return MetaTag
     */
    public function getDescription()
    {
        return $this->tagBuilder->getMeta('description');
    }

    /**
     * @param string $keywords
     *
     * @return $this
     */
    public function setKeywords($keywords)
    {
        $this->tagBuilder->addMeta('keywords')
            ->setType(MetaTag::NAME_TYPE)
            ->setTagName('keywords')
            ->setContent((string) $keywords);

        return $this;
    }

    /**
     * @return MetaTag
     */
    public function getKeywords()
    {
        return $this->tagBuilder->getMeta('keywords');
    }

    /**
     * @param bool $shouldIndex
     * @param bool $shouldFollow
     *
     * @return $this
     */
    public function setRobots($shouldIndex, $shouldFollow)
    {
        $index = $shouldIndex ? 'index' : 'noindex';
        $follow = $shouldFollow ? 'follow' : 'nofollow';

        $this->tagBuilder->addMeta('robots')
            ->setType(MetaTag::NAME_TYPE)
            ->setTagName('robots')
            ->setContent(sprintf('%s, %s', $index, $follow));

        return $this;
    }

    /**
     * @param string $url
     *
     * @return $this
     */
    public function setCanonical($url)
    {
        if ($url) {
            $this->tagBuilder->addLink('canonical')
                ->setHref((string) $url)
                ->setRel('canonical');
        }

        return $this;
    }

    /**
     * @return \Leogout\Bundle\SeoBundle\Model\LinkTag|null
     */
    public function getCanonical()
    {
        return $this->tagBuilder->getLink('canonical');
    }

    /**
     * @param string $url
     *
     * @return $this
     */
    public function setPreviousUrl($url)
    {
        $this->tagBuilder->addLink('previousUrl')
            ->setHref((string) $url)
            ->setRel('prev');

        return $this;
    }

    /**
     * @return \Leogout\Bundle\SeoBundle\Model\LinkTag|null
     */
    public function getPreviousUrl()
    {
        return $this->tagBuilder->getLink('previousUrl');
    }

    /**
     * @param string $url
     *
     * @return $this
     */
    public function setNextUrl($url)
    {
        $this->tagBuilder->addLink('nextUrl')
            ->setHref((string) $url)
            ->setRel('next');

        return $this;
    }

    /**
     * @return \Leogout\Bundle\SeoBundle\Model\LinkTag|null
     */
    public function getNextUrl()
    {
        return $this->tagBuilder->getLink('nextUrl');
    }
    
    /**
     * @param $suffix
     * @return $this
     */
    public function setSuffix($suffix)
    {
        $this->suffix = $suffix;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSuffix()
    {
        return $this->suffix;
    }

    /**
     * @param $separator
     * @return $this
     */
    public function setSeparator($separator)
    {
        $this->separator = $separator;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSeparator()
    {
        return $this->separator;
    }
    
    /**
     * Generate seo tags from given resource.
     *
     * @param TitleSeoInterface|DescriptionSeoInterface|KeywordsSeoInterface $resource
     *
     * @return $this
     */
    public function fromResource($resource)
    {
        if ($resource instanceof TitleSeoInterface) {
            // backward compatibility
            $this->setTitle($resource->getSeoTitle());
        }
        if ($resource instanceof DescriptionSeoInterface) {
            // backward compatibility
            $this->setDescription($resource->getSeoDescription());
        }
        if ($resource instanceof KeywordsSeoInterface) {
            // backward compatibility
            $this->setKeywords($resource->getSeoKeywords());
        }

        // Pagination
        if ($resource instanceof PaginationAwareInterface) {
            $this->setPreviousUrl($resource->getPreviousUrl());
            $this->setNextUrl($resource->getPreviousUrl());
        }

        // Resource
        if ($resource instanceof ResourceInterface) {
            $this->setTitle($resource->getTitle());
            $this->setDescription($resource->getDescription());
            if ($keywords = $resource->getKeywords()) {
                $this->setKeywords((is_array($keywords)) ? $keywords : [$keywords]);
            }
        }

        return $this;
    }
}
