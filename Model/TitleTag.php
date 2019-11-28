<?php

namespace Leogout\Bundle\SeoBundle\Model;
use Leogout\Bundle\SeoBundle\Seo\SeoTranslator;

/**
 * Description of TitleTag.
 *
 * @author: leogout
 */
class TitleTag implements RenderableInterface
{
    protected $translator;
    protected $addSuffix;
    protected $suffix;
    protected $separator;

    /**
     * @var string
     */
    protected $content;

    public function __construct(SeoTranslator $translator, $addSuffix, $suffix, $separator)
    {
        $this->translator = $translator;
        $this->addSuffix = $addSuffix;
        $this->suffix = $suffix;
        $this->separator = $separator;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        if ($this->addSuffix && $this->suffix) {
            $this->content = sprintf('%s %s %s', $this->content, $this->separator, $this->suffix);
        }

        return $this->content;
    }

    /**
     * @param string $content
     *
     * @return $this
     */
    public function setContent($content)
    {        
        $title = (string)$this->translator->trans((string)$content);
        $this->content = mb_convert_case($title, MB_CASE_TITLE, "UTF-8");

        return $this;
    }

    /**
     * @return string
     */
    public function render()
    {
        return sprintf('<title>%s</title>', $this->getContent());
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }
}
