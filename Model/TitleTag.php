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

    /**
     * @var string
     */
    protected $content;

    public function __construct(SeoTranslator $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     *
     * @return $this
     */
    public function setContent($content)
    {
        $this->content = (string) $this->translator->trans((string) $content);

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
