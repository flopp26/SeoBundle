<?php

namespace Leogout\Bundle\SeoBundle\Seo;

use Symfony\Contracts\Translation\TranslatorInterface;

class SeoTranslator
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param $content
     * @return string
     */
    public function trans($content)
    {
        if (strtoupper(substr($content, strlen($content) - 6, 6)) == '|TRANS') {
            $content = str_replace('|trans', '', $content);
            $contentTranslated = $this->translator->trans($content);
            if ($contentTranslated == $content) {
                return null;
            }

            return $contentTranslated;
        }

        return $content;
    }
}
